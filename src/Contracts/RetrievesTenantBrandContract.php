<?php

namespace IVR\MultiTenancyUtils\Contracts;

use IVR\MultiTenancyUtils\Data\Brand\BrandData;

interface RetrievesTenantBrandContract
{
    public function getTenantBrand($tenantKey): ?BrandData;
}
