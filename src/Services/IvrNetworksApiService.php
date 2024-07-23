<?php

namespace IVR\MultiTenancyUtils\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use IVR\MultiTenancyUtils\Constants\BrandDataSource;
use IVR\MultiTenancyUtils\Contracts\RetrievesShopsListContract;
use IVR\MultiTenancyUtils\Contracts\RetrievesTenantBrandContract;
use IVR\MultiTenancyUtils\Data\Brand\BrandData;
use IVR\MultiTenancyUtils\Data\ShopData;
use IVR\MultiTenancyUtils\Support\StaticTenantData;

class IvrNetworksApiService implements RetrievesShopsListContract, RetrievesTenantBrandContract
{

    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl =  config('multitenancy-utils-laravel.crm-api.base_url');
    }


    public function getTenantShops(): Collection
    {
        $tenantKey = app('tenant')->key;
        return Cache::remember("shops:$tenantKey", config('multitenancy-utils-laravel.cache.ttl'), function () use ($tenantKey) {
            $shops = collect();

            $api_response = Http::get("{$this->apiUrl}/api/networks/$tenantKey/shops");
            if ($api_response->successful()) {
                $response_data = json_decode($api_response->body());

                $shops = collect($response_data->data)->map(function ($item) {
                    $item->category_key = Str::slug($item->category);
                    $item->shop_slug = Str::slug($item->name);
                    return ShopData::from($item);
                })->sortBy('shop_slug');
            }

            return $shops;
        });
    }

    public function getTenantBrand(bool $forceDomainDiscovery = false): BrandData
    {
        if (config('multitenancy-utils-laravel.force_static_tenant'))
            return BrandData::from(StaticTenantData::getBrand());

        $discoveryByDomain = (
            config('multitenancy-utils-laravel.domain_discovery.enable')
                && !app()->runningInConsole()
            ) || $forceDomainDiscovery;

        if ($discoveryByDomain)
        {
            $domain = request()->host();
            return $this->getTenantBrandByDomain($domain) ?? $this->getTenantBrandByKey(config('multitenancy-utils-laravel.domain_discovery.fallback_tenant'));
        }

        return $this->getTenantBrandByKey(config('multitenancy-utils-laravel.tenant_key'));
    }

    public function getTenantBrandByKey(string $tenantKey): BrandData
    {
        return Cache::remember("brand:$tenantKey", config('multitenancy-utils-laravel.cache.ttl'), function () use ($tenantKey) {
            try {
                $api_response = Http::get("$this->apiUrl/api/networks/$tenantKey/brand");
                $tenant_data = json_decode($api_response->body())->data;
                $tenant_data->source = BrandDataSource::SYSTEM;
                return BrandData::from($tenant_data);
            } catch (\Exception $exception)
            {
                return BrandData::from(StaticTenantData::getBrand());
            }
        });
    }

    public function getTenantBrandByDomain(string $domain): ?BrandData
    {
        return Cache::remember("brand:$domain", config('multitenancy-utils-laravel.cache.ttl'), function () use ($domain) {
            try {
                $api_response = Http::get("$this->apiUrl/api/networks/brands/find", ['domain' => $domain]);
                $tenant_data = json_decode($api_response->body())->data;
                $tenant_data->source = BrandDataSource::DOMAIN;
                return BrandData::from($tenant_data);
            } catch (\Exception $exception)
            {
                return null;
            }
        });
    }
}
