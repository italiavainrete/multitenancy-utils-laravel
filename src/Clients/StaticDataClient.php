<?php

namespace IVR\Sdk\Clients;

use Illuminate\Support\Collection;
use IVR\Sdk\Clients\Contracts\StaticDataClientContract;
use IVR\Sdk\Data\TenantData;
use RuntimeException;

class StaticDataClient implements StaticDataClientContract
{
    public function getManifest(): array
    {
        throw new RuntimeException('StaticDataClient::getManifest is not implemented yet (Phase 2).');
    }

    public function getTenant(string $slug): TenantData
    {
        throw new RuntimeException('StaticDataClient::getTenant is not implemented yet (Phase 2).');
    }

    public function getShops(string $slug): Collection
    {
        throw new RuntimeException('StaticDataClient::getShops is not implemented yet (Phase 2).');
    }
}
