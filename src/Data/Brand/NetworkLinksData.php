<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

use Spatie\LaravelData\Data;

class NetworkLinksData extends Data
{
    public function __construct(
        public string $main,
        public string $account,
        public ?string $marketplace = null
    )
    {
    }
}
