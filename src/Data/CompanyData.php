<?php

namespace IVR\Sdk\Data;

use Spatie\LaravelData\Data;

class CompanyData extends Data
{
    public function __construct(
        public string $name,
        public string $vat_id,
        public string $address,
        public string $zip_code,
        public string $city,
        public string $province,
        public LogoData $logo,
        public CompanyLinksData $links,
        public ?string $tagline = null,
    ) {}
}
