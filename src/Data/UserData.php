<?php

namespace IVR\MultiTenancyUtils\Data;

use Spatie\LaravelData\Data;

class UserData extends Data
{

    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $avatar,
        public string $cardNumber,
        public string $cardBalance
    )
    {
    }
}
