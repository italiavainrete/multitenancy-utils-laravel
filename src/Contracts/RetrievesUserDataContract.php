<?php

namespace IVR\MultiTenancyUtils\Contracts;

use IVR\MultiTenancyUtils\Data\UserData;

interface RetrievesUserDataContract
{
    /**
     * @throws \IVR\MultiTenancyUtils\Exceptions\InvalidUserTokenException when the identity provider rejects the token
     */
    public function getUserData(string $authToken): UserData;
}
