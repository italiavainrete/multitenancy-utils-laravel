<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use IVR\MultiTenancyUtils\Contracts\RetrievesUserDataContract;
use IVR\MultiTenancyUtils\Exceptions\InvalidUserTokenException;
use IVR\MultiTenancyUtils\Http\Middleware\EnsureValidUserToken;
use IVR\MultiTenancyUtils\Tests\Fixtures\OAuthUser;

beforeEach(function () {
    config()->set('app.key', 'base64:'.base64_encode(str_repeat('k', 32)));
    config()->set('multitenancy-utils-laravel.force_static_tenant', true);
    config()->set('auth.providers.users.model', OAuthUser::class);
    config()->set('database.connections.testing', ['driver' => 'sqlite', 'database' => ':memory:']);

    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('email');
        $table->text('oauth_token')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });

    Route::middleware(['web', EnsureValidUserToken::class])
        ->get('/probe', fn () => Auth::check() ? 'auth' : 'guest');
    Route::get('/login', fn () => 'login page')->name('login');
});

function profilePayload(): array
{
    return ['data' => [
        'id' => 7,
        'email' => 'mario@example.com',
        'avatar' => null,
        'customer' => ['firstname' => 'Mario', 'lastname' => 'Rossi'],
        'loyalty_profile' => ['card_number' => '0001'],
    ]];
}

function oauthUser(?string $token = 'good-token'): OAuthUser
{
    return OAuthUser::create(['email' => 'mario@example.com', 'oauth_token' => $token, 'remember_token' => 'remember-me']);
}

it('maps the profile and the card balance', function () {
    Http::fake([
        '*/api/profile' => Http::response(profilePayload()),
        '*/api/loyalty/card' => Http::response(['data' => ['balance' => ['balance' => 12.5]]]),
    ]);

    $data = app(RetrievesUserDataContract::class)->getUserData('good-token');

    expect($data->id)->toBe('7')
        ->and($data->name)->toBe('Mario Rossi')
        ->and($data->cardNumber)->toBe('0001')
        ->and($data->cardBalance)->toBe('12.5');
});

it('memoizes the lookup within the request', function () {
    Http::fake([
        '*/api/profile' => Http::response(profilePayload()),
        '*/api/loyalty/card' => Http::response([], 500),
    ]);

    $service = app(RetrievesUserDataContract::class);
    $service->getUserData('good-token');
    $data = app(RetrievesUserDataContract::class)->getUserData('good-token');

    expect($data->cardBalance)->toBe('N/A');
    Http::assertSentCount(2);
});

it('throws a typed exception when the token is rejected or the response is invalid', function (int $status, mixed $body) {
    Http::fake(['*/api/profile' => Http::response($body, $status)]);

    app(RetrievesUserDataContract::class)->getUserData('bad-token');
})->with([
    '401' => [401, ['message' => 'Unauthenticated.']],
    '403' => [403, ''],
    'empty body' => [200, ''],
    'html body' => [200, '<html>login</html>'],
    'no data' => [200, ['data' => []]],
])->throws(InvalidUserTokenException::class);

it('throws a typed exception for an empty token', function () {
    app(RetrievesUserDataContract::class)->getUserData('');
})->throws(InvalidUserTokenException::class);

it('returns an unknown user when the identity provider is down', function () {
    Http::fake(['*/api/profile' => Http::response('', 503)]);
    expect(app(RetrievesUserDataContract::class)->getUserData('good-token')->name)->toBe('Sconosciuto');

    Http::fake(['*/api/profile' => fn () => throw new ConnectionException('timeout')]);
    expect(app(RetrievesUserDataContract::class)->getUserData('other-token')->name)->toBe('Sconosciuto');
});

it('keeps a user with a valid token logged in', function () {
    Http::fake([
        '*/api/profile' => Http::response(profilePayload()),
        '*/api/loyalty/card' => Http::response(['data' => ['balance' => ['balance' => 1]]]),
    ]);

    $this->actingAs(oauthUser())->get('/probe')->assertOk()->assertSee('auth');
});

it('logs out a user whose token is rejected and serves the page as guest', function () {
    Http::fake(['*/api/profile' => Http::response(['message' => 'Unauthenticated.'], 401)]);
    $user = oauthUser();

    $this->actingAs($user)->get('/probe')->assertOk()->assertSee('guest');

    $this->assertGuest();
    expect($user->fresh()->oauth_token)->toBeNull()
        ->and($user->fresh()->remember_token)->toBeNull();
});

it('clears the remember token even if the token column is not nullable', function () {
    Schema::drop('users');
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('email');
        $table->text('oauth_token');
        $table->rememberToken();
        $table->timestamps();
    });
    Http::fake(['*/api/profile' => Http::response('', 401)]);
    $user = oauthUser();

    $this->actingAs($user)->get('/probe')->assertOk()->assertSee('guest');

    $this->assertGuest();
    expect($user->fresh()->remember_token)->toBeNull();
});

it('logs out a user without a token', function () {
    Http::fake();

    $this->actingAs(oauthUser(null))->get('/probe')->assertOk()->assertSee('guest');

    $this->assertGuest();
    Http::assertNothingSent();
});

it('can redirect to the login route instead', function () {
    config()->set('multitenancy-utils-laravel.user_token.on_invalid', 'redirect');
    Http::fake(['*/api/profile' => Http::response('', 403)]);

    $this->actingAs(oauthUser())->get('/probe')->assertRedirect('/login');
    $this->assertGuest();
});

it('keeps the session when the identity provider is down', function () {
    Http::fake(['*/api/profile' => Http::response('', 502)]);
    $user = oauthUser();

    $this->actingAs($user)->get('/probe')->assertOk()->assertSee('auth');
    expect($user->fresh()->oauth_token)->toBe('good-token');
});

it('caches successful validations when a ttl is configured', function () {
    config()->set('multitenancy-utils-laravel.user_token.validation_ttl', 60);
    Http::fake([
        '*/api/profile' => Http::response(profilePayload()),
        '*/api/loyalty/card' => Http::response(['data' => ['balance' => ['balance' => 1]]]),
    ]);
    $user = oauthUser();

    $this->actingAs($user)->get('/probe')->assertSee('auth');
    $this->app->forgetScopedInstances();
    $this->actingAs($user)->get('/probe')->assertSee('auth');

    Http::assertSentCount(2);
});

it('registers the middleware alias', function () {
    expect(app('router')->getMiddleware())->toHaveKey('ivr.user-token', EnsureValidUserToken::class);
});
