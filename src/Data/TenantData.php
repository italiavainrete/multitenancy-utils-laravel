<?php

namespace IVR\Sdk\Data;

use Spatie\LaravelData\Data;

class TenantData extends Data
{
    public function __construct(
        public string $slug,
        public string $name,
        public array $domains,
        public string $campaign_id,
        public array $backgrounds,
        public array $allowed_tenants,
        public NetworkLinksData $links,
        public ContactsData $contacts,
        public CardData $card,
        public CompanyData $company,
    ) {}

    public function pickBackgroundImage(): ?string
    {
        $count = count($this->backgrounds);

        return $count > 0 ? $this->backgrounds[random_int(0, $count - 1)] : null;
    }

    public function crossNetworkLoginAllowed(string $tenantSlug): bool
    {
        return in_array($tenantSlug, $this->allowed_tenants, true);
    }
}
