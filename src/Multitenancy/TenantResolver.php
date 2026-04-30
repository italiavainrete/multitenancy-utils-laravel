<?php

namespace IVR\Sdk\Multitenancy;

use IVR\Sdk\Clients\Contracts\StaticDataClientContract;
use IVR\Sdk\Multitenancy\Contracts\TenantResolverContract;

class TenantResolver implements TenantResolverContract
{
    /** @var array<string, string>|null */
    private ?array $domainToSlug = null;

    public function __construct(
        private readonly StaticDataClientContract $staticData,
    ) {}

    public function resolveFromHost(string $host): ?string
    {
        return $this->domainMap()[$host] ?? null;
    }

    public function allDomains(): array
    {
        return array_keys($this->domainMap());
    }

    /**
     * @return array<string, string>
     */
    private function domainMap(): array
    {
        if ($this->domainToSlug !== null) {
            return $this->domainToSlug;
        }

        $map = [];
        foreach ($this->staticData->getManifest() as $slug => $domains) {
            foreach ($domains as $domain) {
                $map[$domain] = $slug;
            }
        }

        return $this->domainToSlug = $map;
    }
}
