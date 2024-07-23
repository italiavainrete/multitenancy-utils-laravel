<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

use IVR\MultiTenancyUtils\Constants\BrandDataSource;
use IVR\MultiTenancyUtils\Support\RandomImageProvider;
use Spatie\LaravelData\Data;

class BrandData extends Data
{


    public function __construct(
        public string       $name,
        public string       $key,
        public ContactsData $contacts,
        public LogoData     $logo,
        public FontData     $font,
        public ColorData    $colors,
        public FaviconData  $favicon_data,
        public CardData     $card,
        public CompanyData  $company,
        public array   $backgrounds,
        public array   $domains,
        public array   $allowed_tenants,
        public string $campaign_id,
        public BrandDataSource $source = BrandDataSource::DEFAULT
    )
    {
    }

    public function renderWebManifest(): array
    {
        return $this->favicon_data->renderWebManifest($this);
    }

    public function renderBrowserConfigXml(): string
    {
        return $this->favicon_data->renderBrowserConfigXml($this);
    }

    public function renderFaviconMeta(): string
    {
        return $this->favicon_data->renderFaviconMetaTags($this);
    }

    public function pickBackgroundImage(): string
    {
        $count = count($this->backgrounds);
        return$count > 0 ?
            $this->backgrounds[rand(0, $count - 1)]
            : RandomImageProvider::get();
    }

    public function getCardImageUrl(): string
    {
        return $this->card->front;
    }

    public function asset(string $fileName): string
    {
        return config('multitenancy-utils-laravel.cdn') . $this->key . '/assets/' . $fileName;
    }

    public function crossNetworkLoginAllowed(string $tenantKey): bool
    {
        return array_key_exists($tenantKey, $this->domains);
    }
}
