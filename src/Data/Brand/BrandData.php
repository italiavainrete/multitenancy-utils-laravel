<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

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
        public string $campaign_id
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

}
