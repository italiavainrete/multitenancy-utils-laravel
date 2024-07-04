<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

use Illuminate\Support\Collection;
use IVR\MultiTenancyUtils\Services\RandomImageProvider;
use Spatie\LaravelData\Data;

class BrandData extends Data
{


    public function __construct(
        public string       $name,
        public string       $key,
        public ContactsData $contacts,
        public LogoData     $logo,
        public FontData     $font,
        public ColorData    $colors,
        public FaviconData  $favicon_data,
        public CardData     $card,
        public CompanyData  $company,
        public array   $backgrounds,
        public array   $domains,
        public array   $allowed_tenants
    )
    {
    }


}
