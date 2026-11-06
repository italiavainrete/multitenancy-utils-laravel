<?php

namespace IVR\MultiTenancyUtils\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use IVR\MultiTenancyUtils\Contracts\RetrievesUserDataContract;
use IVR\MultiTenancyUtils\Data\UserData;
use IVR\MultiTenancyUtils\Exceptions\InvalidUserTokenException;
use Throwable;

class UserApiService implements RetrievesUserDataContract
{
    /**
     * Successful lookups for the current request, keyed by token, so the
     * middleware and the layout do not hit the identity provider twice.
     *
     * @var array<string, UserData>
     */
    private array $resolved = [];

    /**
     * @throws InvalidUserTokenException when the identity provider does not accept the token
     */
    public function getUserData(string $authToken): UserData
    {
        if (trim($authToken) === '') {
            throw InvalidUserTokenException::emptyToken();
        }

        if (isset($this->resolved[$authToken])) {
            return $this->resolved[$authToken];
        }

        try {
            $profileResponse = $this->prepareRequest($authToken)->get('/api/profile');
        } catch (Throwable $exception) {
            // Identity provider unreachable: not the user's fault, keep the session.
            Log::warning('User profile request failed', ['exception' => $exception->getMessage()]);

            return $this->unknownUser();
        }

        $user = $this->profileFrom($profileResponse);

        if ($user === null) {
            // Server-side error on the identity provider: keep the session.
            Log::warning('User profile request failed', ['status' => $profileResponse->status()]);

            return $this->unknownUser();
        }

        $name = trim(data_get($user, 'customer.firstname', '').' '.data_get($user, 'customer.lastname', ''));
        $name = $name !== '' ? $name : 'Sconosciuto';

        return $this->resolved[$authToken] = new UserData(
            id: (string) data_get($user, 'id', ''),
            name: $name,
            email: (string) data_get($user, 'email', ''),
            avatar: (string) (data_get($user, 'avatar') ?: 'https://api.dicebear.com/9.x/initials/svg?seed='.$name),
            cardNumber: (string) (data_get($user, 'loyalty_profile.card_number') ?? 'N/A'),
            cardBalance: $this->cardBalance($authToken),
        );
    }

    /**
     * Returns the profile payload, null on transient errors (5xx, 429), and throws
     * when the identity provider refuses the token.
     *
     * @return array<string, mixed>|null
     */
    private function profileFrom(Response $response): ?array
    {
        if (in_array($response->status(), [401, 403], true)) {
            throw InvalidUserTokenException::rejected($response->status());
        }

        if ($response->serverError() || $response->status() === 429) {
            return null;
        }

        $user = $response->successful() ? $response->json('data') : null;

        if (! is_array($user) || $user === []) {
            throw InvalidUserTokenException::invalidResponse($response->status());
        }

        return $user;
    }

    private function cardBalance(string $authToken): string
    {
        try {
            $response = $this->prepareRequest($authToken)->get('/api/loyalty/card');
        } catch (Throwable) {
            return 'N/A';
        }

        $balance = $response->successful() ? $response->json('data.balance.balance') : null;

        return is_scalar($balance) ? (string) $balance : 'N/A';
    }

    private function unknownUser(): UserData
    {
        return new UserData(
            id: '',
            name: 'Sconosciuto',
            email: '',
            avatar: 'https://api.dicebear.com/9.x/initials/svg?seed=Sconosciuto',
            cardNumber: 'N/A',
            cardBalance: 'N/A',
        );
    }

    private function prepareRequest(string $authToken): PendingRequest
    {
        return Http::withToken($authToken)
            ->acceptJson()
            ->timeout((int) config('multitenancy-utils-laravel.user_token.timeout', 10))
            ->baseUrl(app('tenant')->links->account);
    }
}
