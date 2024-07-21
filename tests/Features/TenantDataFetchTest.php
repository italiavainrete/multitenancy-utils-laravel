<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use IVR\MultiTenancyUtils\Constants\BrandDataSource;
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
    $brand = $service->getTenantBrand();

    expect($brand)->toBeInstanceOf(BrandData::class)
        ->and($brand->name)->toBe(Tenants::IVR_NAME)
        ->and(Cache::has("brand:$tenantKey"))->toBeTrue()
        ->and(app('tenant')->source)->toEqual(BrandDataSource::SYSTEM);
});

it('retrieves tenant brand from domain name', function () {
    Config::set('multitenancy-utils-laravel.domain_discovery.enable', true);
    Http::fake([
        '*networks/brands/find*' => Http::response([
            'data' => json_decode(file_get_contents(__DIR__. '/../../src/StaticData/brand-velletri.json'))
        ], 200)
    ]);
    $test_domain = 'www.velletrivainrete.it';
    $request = Request::instance();
    $request->headers->set('HOST', $test_domain);
    $this->app->instance('request', $request);


    $service = app(RetrievesTenantBrandContract::class);
    $brand = $service->getTenantBrand(true);

    expect($brand)->toBeInstanceOf(BrandData::class)
        ->and($brand->name)->toBe('Velletri va In Rete')
        ->and(Cache::has("brand:$test_domain"))->toBeTrue()
        ->and(app('tenant')->source)->toEqual(BrandDataSource::DOMAIN);
});

it('will serve static tenant brand config options is set', function () {
    Config::set('multitenancy-utils-laravel.force_static_tenant', true);


    $service = app(RetrievesTenantBrandContract::class);
    $brand = $service->getTenantBrand();

    expect($brand)->toBeInstanceOf(BrandData::class)
        ->and($brand->name)->toBe(Tenants::IVR_NAME)
        ->and(Cache::has("brand:" .Tenants::IVR_KEY))->toBeFalse()
        ->and(app('tenant')->source)->toEqual(BrandDataSource::DEFAULT);
});
