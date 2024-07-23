<?php

namespace IVR\MultiTenancyUtils\Contracts;

use IVR\MultiTenancyUtils\Data\UserData;

interface RetrievesUserDataContract
{
    public function getUserData(string $authToken): UserData;
}
