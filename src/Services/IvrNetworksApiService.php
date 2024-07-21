<?php

namespace IVR\MultiTenancyUtils\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use IVR\MultiTenancyUtils\Contracts\RetrievesShopsListContract;
use IVR\MultiTenancyUtils\Contracts\RetrievesTenantBrandContract;
use IVR\MultiTenancyUtils\Data\Brand\BrandData;
use IVR\MultiTenancyUtils\Data\ShopData;

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

    public function getTenantBrand($tenantKey): ?BrandData
    {
        return Cache::remember("brand:$tenantKey", config('multitenancy-utils-laravel.cache.ttl'), function () use ($tenantKey) {
            try {
                $api_response = Http::get("$this->apiUrl/api/networks/$tenantKey/brand");
                $tenant_data = json_decode($api_response->body())->data;
                return BrandData::from($tenant_data);
            } catch (\Exception $exception)
            {
                return null;
            }
        });
    }
}
