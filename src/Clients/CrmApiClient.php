<?php

namespace IVR\Sdk\Clients;

use Illuminate\Support\Collection;
use IVR\Sdk\Clients\Contracts\CrmApiClientContract;
use RuntimeException;

class CrmApiClient implements CrmApiClientContract
{
    public function getPage(string $tenantSlug, string $pagePath): ?array
    {
        throw new RuntimeException('CrmApiClient::getPage is not implemented yet (Phase 2).');
    }

    public function getSubscriptions(string $tenantSlug): Collection
    {
        throw new RuntimeException('CrmApiClient::getSubscriptions is not implemented yet (Phase 2).');
    }

    public function getCrossFeatures(string $tenantSlug): Collection
    {
        throw new RuntimeException('CrmApiClient::getCrossFeatures is not implemented yet (Phase 2).');
    }
}
