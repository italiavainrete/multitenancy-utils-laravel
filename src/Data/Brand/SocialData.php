<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

use Spatie\LaravelData\Data;

class SocialData extends Data
{

    public function __construct(
        public string $link,
        public string $type
    )
    {
    }
}
