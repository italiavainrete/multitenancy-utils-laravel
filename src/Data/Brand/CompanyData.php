<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

use Spatie\LaravelData\Data;

class CompanyData extends Data
{

    public function __construct(
        public string           $name,
        public ?string          $tagline = null,
        public LogoData         $logo,
        public string           $vat_id,
        public string           $address,
        public string           $zip_code,
        public string           $city,
        public string           $province,
        public CompanyLinksData $links
    )
    {
    }
}
