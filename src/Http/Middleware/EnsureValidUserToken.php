<?php

namespace IVR\MultiTenancyUtils\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use IVR\MultiTenancyUtils\Contracts\RetrievesUserDataContract;
use IVR\MultiTenancyUtils\Exceptions\InvalidUserTokenException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Disconnects the local user when the identity provider no longer accepts
 * the OAuth token stored on it (revoked token, rotated keys, ...).
 *
 * Must run after StartSession: append it to the `web` group. On routes
 * protected by `auth` it throws AuthenticationException after the logout,
 * so guests get the usual redirect to login (or 401 for JSON).
 */
class EnsureValidUserToken
{
    public function __construct(private readonly RetrievesUserDataContract $userData) {}

    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard(config('multitenancy-utils-laravel.user_token.guard'));
        $user = $guard->user();

        if ($user === null) {
            return $next($request);
        }

        $attribute = config('multitenancy-utils-laravel.user_token.attribute', 'oauth_token');

        $token = (string) data_get($user, $attribute, '');
        $ttl = (int) config('multitenancy-utils-laravel.user_token.validation_ttl', 0);

        try {
            if ($ttl > 0 && $token !== '') {
                // Only successful validations are cached: a rejection throws.
                Cache::remember('ivr-user-token:'.hash('sha256', $token), $ttl, function () use ($token) {
                    $this->userData->getUserData($token);

                    return true;
                });
            } else {
                $this->userData->getUserData($token);
            }
        } catch (InvalidUserTokenException $exception) {
            $this->disconnect($request, $guard, $user, $attribute, $exception);

            return $this->respondAsGuest($request, $next);
        }

        return $next($request);
    }

    private function disconnect(Request $request, Guard $guard, Authenticatable $user, string $attribute, InvalidUserTokenException $exception): void
    {
        Log::info('Disconnecting user with an invalid OAuth token', [
            'user' => $user->getAuthIdentifier(),
            'reason' => $exception->getMessage(),
        ]);

        if ($user instanceof Model) {
            // Saved separately: if the token column is not nullable the remember
            // token must still be cleared, so a stolen remember cookie stops working.
            if ($user->getRememberTokenName()) {
                $this->clearAttribute($user, $user->getRememberTokenName());
            }
            $this->clearAttribute($user, $attribute);
        }

        if (method_exists($guard, 'logout')) {
            $guard->logout();
        }

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
    }

    private function clearAttribute(Model $user, string $attribute): void
    {
        try {
            $user->forceFill([$attribute => null])->saveQuietly();
        } catch (Throwable $exception) {
            $user->forceFill([$attribute => $user->getOriginal($attribute)]);
            report($exception);
        }
    }

    private function respondAsGuest(Request $request, Closure $next): Response
    {
        $route = config('multitenancy-utils-laravel.user_token.redirect_route');

        if (config('multitenancy-utils-laravel.user_token.on_invalid') === 'redirect'
            && $route && Route::has($route) && ! $request->expectsJson()) {
            return redirect()->route($route);
        }

        // `auth` is higher in the middleware priority, so on protected routes it
        // has already let the stale user through: fail the way it would for a guest.
        if ($this->routeRequiresAuthentication($request)) {
            throw new AuthenticationException('Unauthenticated.');
        }

        return $next($request);
    }

    private function routeRequiresAuthentication(Request $request): bool
    {
        foreach ($request->route()?->gatherMiddleware() ?? [] as $middleware) {
            if (! is_string($middleware)) {
                continue;
            }

            $name = explode(':', $middleware, 2)[0];

            if ($name === 'auth' || is_a($name, AuthenticatesRequests::class, true)) {
                return true;
            }
        }

        return false;
    }
}
