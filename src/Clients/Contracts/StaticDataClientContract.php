<?php

namespace IVR\Sdk\Clients\Contracts;

use Illuminate\Support\Collection;
use IVR\Sdk\Data\TenantData;

interface StaticDataClientContract
{
    /**
     * Map<slug, domain[]> as published in tenants/index.json.
     *
     * @return array<string, array<int, string>>
     */
    public function getManifest(): array;

    public function getTenant(string $slug): TenantData;

    /**
     * @return Collection<int, \IVR\Sdk\Data\ShopData>
     */
    public function getShops(string $slug): Collection;
}
