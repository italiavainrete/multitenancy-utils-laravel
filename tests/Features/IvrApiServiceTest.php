<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use IVR\MultiTenancyUtils\Data\Brand\BrandData;
use IVR\MultiTenancyUtils\Services\IvrNetworksApiService;
use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;

beforeEach(function () {
    // Configurazione di default per ogni test, se necessario
    Cache::flush();
});

it('retrieves tenant shops and caches the result', function () {
    // Mock delle risposte HTTP
    Http::fake([
        '*/api/networks/*/shops' => Http::response([
            'data' => [
                ['name' => 'Shop 1', 'category' => 'Category 1'],
                ['name' => 'Shop 2', 'category' => 'Category 2'],
            ]
        ], 200)
    ]);

    $tenantKey = 'tenant-key';
    $service = new IvrNetworksApiService();
    $shops = $service->getTenantShops($tenantKey);

    expect($shops)->toBeCollection();
    expect($shops->first()->shop_slug)->toBe('shop-1');

    // Verifica che i dati siano stati memorizzati nella cache
    expect(Cache::has("shops:$tenantKey"))->toBeTrue();
});

it('retrieves tenant brand and caches the result', function () {
    // Mock delle risposte HTTP
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => json_decode(file_get_contents(__DIR__ . '/../json/brand.json'))
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
