<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use IVR\MultiTenancyUtils\Constants\Tenants;
use IVR\MultiTenancyUtils\Contracts\RetrievesShopsListContract;
use IVR\MultiTenancyUtils\Contracts\RetrievesTenantBrandContract;
use IVR\MultiTenancyUtils\Data\Brand\BrandData;
use IVR\MultiTenancyUtils\Data\ShopData;
use IVR\MultiTenancyUtils\Support\StaticTenantData;

beforeEach(function () {
    // Configurazione di default per ogni test, se necessario
    Cache::flush();
});

it('retrieves tenant shops and caches the result', function () {
    // Mock delle risposte HTTP
    Http::fake([
        '*/api/networks/*/shops' => Http::response([
            'data' => StaticTenantData::getShops()
        ], 200)
    ]);

    $service = app(RetrievesShopsListContract::class);
    $shops = $service->getTenantShops();

    expect($shops)->toBeCollection()
        ->and($shops->first())->toBeInstanceOf(ShopData::class)
        ->and($shops->first()->shop_slug)->toBe('ceramiche-di-deruta')
        ->and(Cache::has("shops:".Tenants::IVR_KEY))->toBeTrue();

    // Verifica che i dati siano stati memorizzati nella cache
});

it('retrieves tenant brand and caches the result', function () {
    // Mock delle risposte HTTP
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => StaticTenantData::getBrand()
        ], 200)
    ]);

    $tenantKey = config('multitenancy-utils-laravel.tenant_key');
    $service = app(RetrievesTenantBrandContract::class);
    $brand = $service->getTenantBrand($tenantKey);

    expect($brand)->toBeInstanceOf(BrandData::class)
        ->and($brand->name)->toBe(Tenants::IVR_NAME)
        ->and(Cache::has("brand:$tenantKey"))->toBeTrue();
});
