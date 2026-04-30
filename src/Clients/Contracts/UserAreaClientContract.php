<?php

namespace IVR\Sdk\Clients\Contracts;

use IVR\Sdk\Data\UserData;

interface UserAreaClientContract
{
    public function getProfile(string $authToken): UserData;

    /**
     * @return array<string, mixed>
     */
    public function getLoyaltyCard(string $authToken): array;

    /**
     * @return array<string, mixed>
     */
    public function getMovements(string $authToken, int $page = 1): array;
}
