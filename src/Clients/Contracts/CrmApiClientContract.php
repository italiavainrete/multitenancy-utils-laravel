<?php

namespace IVR\Sdk\Clients\Contracts;

use Illuminate\Support\Collection;

interface CrmApiClientContract
{
    /**
     * Live CMS page payload for the given tenant slug + page path.
     * Returns null when the page is missing.
     */
    public function getPage(string $tenantSlug, string $pagePath): ?array;

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function getSubscriptions(string $tenantSlug): Collection;

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function getCrossFeatures(string $tenantSlug): Collection;
}
