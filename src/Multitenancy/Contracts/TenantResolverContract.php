<?php

namespace IVR\Sdk\Multitenancy\Contracts;

interface TenantResolverContract
{
    /**
     * Returns the tenant slug for the given request host, or null if unknown.
     */
    public function resolveFromHost(string $host): ?string;

    /**
     * Flat list of all known domains across tenants. Useful for diagnostics.
     *
     * @return array<int, string>
     */
    public function allDomains(): array;
}
