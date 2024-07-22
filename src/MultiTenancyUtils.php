<?php

namespace IVR\MultiTenancyUtils;

use IVR\MultiTenancyUtils\Services\IvrNetworksApiService;
use IVR\MultiTenancyUtils\Support\CdnUtils;

class MultiTenancyUtils
{
    static function asset(string $fileName): string
    {
       return CdnUtils::asset($fileName);
    }

    static function productImage(string $fileName): string
    {
        return CdnUtils::product($fileName);
    }

    static function shopImage(string $fileName): string
    {
        return CdnUtils::shop($fileName);
    }

    static function getBrandData(): ?Data\Brand\BrandData
    {
        $service = new IvrNetworksApiService;
        return $service->getTenantBrand();
    }
}
