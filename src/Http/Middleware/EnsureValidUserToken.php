<?php

namespace IVR\MultiTenancyUtils\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
 * Must run after StartSession: append it to the `web` group.
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

        try {
            $this->userData->getUserData((string) data_get($user, $attribute, ''));
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

        return $next($request);
    }
}
