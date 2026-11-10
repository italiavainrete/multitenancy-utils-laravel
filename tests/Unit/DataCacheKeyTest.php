<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use IVR\MultiTenancyUtils\Constants\Tenants;
use IVR\MultiTenancyUtils\Contracts\RetrievesTenantBrandContract;
use IVR\MultiTenancyUtils\Data\Brand\BrandData;
use IVR\MultiTenancyUtils\Data\Brand\FaviconData;
use IVR\MultiTenancyUtils\Data\ShopData;
use IVR\MultiTenancyUtils\Support\DataCacheKey;
use IVR\MultiTenancyUtils\Support\StaticTenantData;
use Spatie\LaravelData\Data;

it('builds stable keys carrying the shape fingerprint of the cached class', function () {
    $key = DataCacheKey::for('brand', BrandData::class, Tenants::IVR_KEY);

    expect($key)->toMatch('/^brand:[0-9a-f]{12}:'.preg_quote(Tenants::IVR_KEY, '/').'$/')
        ->and(DataCacheKey::for('brand', BrandData::class, Tenants::IVR_KEY))->toBe($key)
        ->and(DataCacheKey::fingerprint(BrandData::class))->not->toBe(DataCacheKey::fingerprint(ShopData::class));
});

it('changes the fingerprint when the class shape changes', function () {
    // Same situation as FaviconData gaining icons_base_url / has_svg_icon.
    $old = get_class(new class('#fff') extends Data
    {
        public function __construct(public ?string $color) {}
    });
    $new = get_class(new class('#fff') extends Data
    {
        public function __construct(public ?string $color, public bool $has_svg_icon = false) {}
    });

    expect(DataCacheKey::fingerprint($old))->not->toBe(DataCacheKey::fingerprint($new));
});

it('includes the nested Data classes in the brand fingerprint', function () {
    $seen = [];
    $shape = (new ReflectionMethod(DataCacheKey::class, 'shape'))->invokeArgs(null, [BrandData::class, &$seen]);

    expect($shape)->toHaveKey(FaviconData::class.'::$has_svg_icon')
        ->toHaveKey(FaviconData::class.'::$icons_base_url')
        ->toHaveKey(BrandData::class.'::$favicon_data');
});

it('ignores a brand cached under the pre-fingerprint key written by an older release', function () {
    config()->set('multitenancy-utils-laravel.force_static_tenant', false);
    config()->set('multitenancy-utils-laravel.domain_discovery.enable', false);
    $tenantKey = config('multitenancy-utils-laravel.tenant_key');

    // An older release cached the brand under "brand:<key>": that entry must not be read anymore.
    Cache::put("brand:$tenantKey", 'stale-object-from-an-older-release', 600);

    Http::fake(['*/api/networks/*/brand' => Http::response(['data' => StaticTenantData::getBrand()], 200)]);

    $brand = app(RetrievesTenantBrandContract::class)->getTenantBrand();

    expect($brand)->toBeInstanceOf(BrandData::class)
        ->and($brand->renderFaviconMeta())->toContain('favicon-32x32.png');
    Http::assertSentCount(1);
});
