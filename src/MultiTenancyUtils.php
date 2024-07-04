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

    static function getBrandData(): ?Data\Brand\BrandData
    {
        $service = new IvrNetworksApiService;
        return $service->getTenantBrand( config('multitenancy-utils-laravel.tenant_key'));
    }
}
