<?php

namespace IVR\MultiTenancyUtils\Contracts;

use IVR\MultiTenancyUtils\Data\Brand\BrandData;

interface RetrievesTenantBrandContract
{
    public function getTenantBrand(bool $forceDomainDiscovery = false): BrandData;

    public function getTenantBrandByKey(string $tenantKey): BrandData;

    public function getTenantBrandByDomain(string $domain): ?BrandData;
}
