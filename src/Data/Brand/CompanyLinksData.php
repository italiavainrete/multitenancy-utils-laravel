<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

use Spatie\LaravelData\Data;

class CompanyLinksData extends Data
{

    public function __construct(
        public string $home,
        public string $about,
        public string $terms_and_conditions,
        public string $privacy_policy
    )
    {
    }
}
