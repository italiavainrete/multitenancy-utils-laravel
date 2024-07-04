<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use IVR\MultiTenancyUtils\Data\Brand\BrandData;
use IVR\MultiTenancyUtils\Services\IvrNetworksApiService;

beforeEach(function () {
    // Configurazione di default per ogni test, se necessario
    Cache::flush();
});

it('retrieves tenant shops and caches the result', function () {
    // Mock delle risposte HTTP
    Http::fake([
        '*/api/networks/*/shops' => Http::response([
            'data' => \IVR\MultiTenancyUtils\Tests\Support\Utils::getTestShopsData()
        ], 200)
    ]);

    $tenantKey = 'tenant-key';
    $service = new IvrNetworksApiService();
    $shops = $service->getTenantShops($tenantKey);

    expect($shops)->toBeCollection();
    expect($shops->first())->toBeInstanceOf(\IVR\MultiTenancyUtils\Data\ShopData::class);
    expect($shops->first()->shop_slug)->toBe('beauty-hair-top');

    // Verifica che i dati siano stati memorizzati nella cache
    expect(Cache::has("shops:$tenantKey"))->toBeTrue();
});

it('retrieves tenant brand and caches the result', function () {
    // Mock delle risposte HTTP
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => \IVR\MultiTenancyUtils\Tests\Support\Utils::getTestBrandData()
        ], 200)
    ]);

    $tenantKey = 'tenant-key';
    $service = new IvrNetworksApiService();
    $brand = $service->getTenantBrand($tenantKey);

    expect($brand)->toBeInstanceOf(BrandData::class);
    expect($brand->name)->toBe('Albano Card');

    // Verifica che i dati siano stati memorizzati nella cache
    expect(Cache::has("brand:$tenantKey"))->toBeTrue();
});
