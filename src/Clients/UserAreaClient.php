<?php

namespace IVR\Sdk\Clients;

use IVR\Sdk\Clients\Contracts\UserAreaClientContract;
use IVR\Sdk\Data\UserData;
use RuntimeException;

class UserAreaClient implements UserAreaClientContract
{
    public function getProfile(string $authToken): UserData
    {
        throw new RuntimeException('UserAreaClient::getProfile is not implemented yet (Phase 2).');
    }

    public function getLoyaltyCard(string $authToken): array
    {
        throw new RuntimeException('UserAreaClient::getLoyaltyCard is not implemented yet (Phase 2).');
    }

    public function getMovements(string $authToken, int $page = 1): array
    {
        throw new RuntimeException('UserAreaClient::getMovements is not implemented yet (Phase 2).');
    }
}
