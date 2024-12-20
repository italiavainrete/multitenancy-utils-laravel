<?php

namespace IVR\MultiTenancyUtils\Services;

use Illuminate\Support\Facades\Http;
use IVR\MultiTenancyUtils\Contracts\RetrievesUserDataContract;
use IVR\MultiTenancyUtils\Data\UserData;

class UserApiService implements RetrievesUserDataContract
{
    public function getUserData(string $authToken): UserData
    {
        $profile_response = $this->prepareRequest($authToken)
            ->get("/api/profile");
        $card_data_response = $this->prepareRequest($authToken)
            ->get("/api/loyalty/card");

        $user = json_decode($profile_response->body())->data;
        if (!$card_data_response->failed()) {
            $card = json_decode($card_data_response->body())->data;
        }
        $name = $user->customer->firstname . ' ' . $user->customer->lastname;

        return new UserData(
            id: $user->id,
            name: $name,
            email: $user->email,
            avatar: $user->avatar ?? 'https://api.dicebear.com/9.x/initials/svg?seed=' . $name,
            cardNumber: !is_array($user->loyalty_profile) ? $user->loyalty_profile->card_number : 'N/A',
            cardBalance: isset($card) ? $card->balance->balance : 'N/A',
        );
    }

    /**
     * @return \Illuminate\Http\Client\PendingRequest
     */
    private function prepareRequest(string $authToken): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withHeader('Authorization', "Bearer $authToken")
            ->baseUrl(app('tenant')->links->account);
    }
}
