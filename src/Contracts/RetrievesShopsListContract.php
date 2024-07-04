<?php

namespace IVR\MultiTenancyUtils\Contracts;


use Illuminate\Support\Collection;

interface RetrievesShopsListContract
{
    public function getTenantShops($tenantKey): Collection;
}
