<?php

use IVR\MultiTenancyUtils\Constants\Tenants;
use IVR\MultiTenancyUtils\Data\Brand\BrandData;
use IVR\MultiTenancyUtils\Data\Brand\FaviconData;

it('renders browser config XML correctly', function () {
    $cdn = config('multitenancy-utils-laravel.cdn');
    $faviconData = new FaviconData(
        android_theme_color: '#ffffff',
        ms_tile_color: '#ffffff',
        osx_mask_icon_color: '#ffffff'
    );

    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    $brandData = BrandData::from(\IVR\MultiTenancyUtils\Support\StaticTenantData::getBrand());

    $expectedXml = '<?xml version="1.0" encoding="utf-8"?>
            <browserconfig>
                <msapplication>
                    <tile>
                        <square150x150logo src="'.$cdn.'/'.$brandData->key.'/assets/mstile-150x150.png"/>
                        <TileColor>#ffffff</TileColor>
                    </tile>
                </msapplication>
            </browserconfig>';

    $actualXml = $faviconData->renderBrowserConfigXml($brandData);

    $expectedXml = preg_replace('/\s+/', '', $expectedXml);
    $actualXml = preg_replace('/\s+/', '', $actualXml);

    expect($actualXml)->toBe($expectedXml);
});

it('renders web manifest correctly', function () {
    $cdn = config('multitenancy-utils-laravel.cdn');
    $faviconData = new FaviconData(
        android_theme_color: '#ffffff',
        ms_tile_color: '#ffffff',
        osx_mask_icon_color: '#ffffff'
    );

    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    $brandData = BrandData::from(\IVR\MultiTenancyUtils\Support\StaticTenantData::getBrand());

    $expectedManifest = [
        "name" => "$brandData->name",
        "short_name" => "$brandData->key",
        "icons" => [
            [
                "src" => "$cdn/$brandData->key/assets/android-chrome-192x192.png",
                "sizes" => "192x192",
                "type" => "image/png"
            ],
            [
                "src" => "$cdn/$brandData->key/assets/android-chrome-512x512.png",
                "sizes" => "512x512",
                "type" => "image/png"
            ]
        ],
        "theme_color" => "#ffffff",
        "background_color" => "#ffffff",
        "display" => "standalone"
    ];

    expect($faviconData->renderWebManifest($brandData))->toBe($expectedManifest);
});

it('renders favicon meta tags correctly', function () {
    $cdn = config('multitenancy-utils-laravel.cdn');
    $faviconData = new FaviconData(
        android_theme_color: '#ffffff',
        ms_tile_color: '#ffffff',
        osx_mask_icon_color: '#ffffff'
    );

    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    $brandData = BrandData::from(\IVR\MultiTenancyUtils\Support\StaticTenantData::getBrand());

    $expectedMetaTags = '
        <link rel="shortcut icon" href="'.$cdn.'/'.$brandData->key.'/assets/favicon.ico">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="apple-touch-icon" sizes="180x180" href="'.$cdn.'/'.$brandData->key.'/assets/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="'.$cdn.'/'.$brandData->key.'/assets/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="'.$cdn.'/'.$brandData->key.'/assets/favicon-16x16.png">

        <meta name="msapplication-config" content="/browserconfig.xml">
        <meta name="apple-mobile-web-app-title" content="'.$brandData->name.'">
        <meta name="application-name" content="'.$brandData->name.'">

        <link rel="mask-icon" href="'.$cdn.'/'.$brandData->key.'/assets/safari-pinned-tab.svg" color="#ffffff">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="theme-color" content="#ffffff">
    ';

    $actualMetaTags = $faviconData->renderFaviconMetaTags($brandData);

    $expectedMetaTags = preg_replace('/\s+/', '', $expectedMetaTags);
    $actualMetaTags = preg_replace('/\s+/', '', $actualMetaTags);

    expect($actualMetaTags)->toBe($expectedMetaTags);
});

it('uses icons_base_url for every icon when provided', function () {
    $faviconData = new FaviconData(
        android_theme_color: '#ffffff',
        ms_tile_color: '#ffffff',
        osx_mask_icon_color: '#ffffff',
        icons_base_url: 'https://cdn.example.com/siaz/icons/'
    );

    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    $brandData = BrandData::from(\IVR\MultiTenancyUtils\Support\StaticTenantData::getBrand());
    $base = 'https://cdn.example.com/siaz/icons';

    $tags = $faviconData->renderFaviconMetaTags($brandData);
    expect($tags)
        ->toContain($base.'/favicon.ico')
        ->toContain($base.'/apple-touch-icon.png')
        ->toContain($base.'/safari-pinned-tab.svg')
        ->toContain($base.'/favicon-32x32.png')
        ->not->toContain('/assets/');

    expect($faviconData->renderBrowserConfigXml($brandData))->toContain($base.'/mstile-150x150.png');
    expect($faviconData->renderWebManifest($brandData)['icons'][0]['src'])->toBe($base.'/android-chrome-192x192.png');
});

it('falls back to tenant assets when icons_base_url is empty or missing from payload', function () {
    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    $cdn = config('multitenancy-utils-laravel.cdn');

    foreach ([null, ''] as $value) {
        $faviconData = FaviconData::from([
            'android_theme_color' => '#ffffff',
            'ms_tile_color' => '#ffffff',
            'osx_mask_icon_color' => '#ffffff',
            'icons_base_url' => $value,
        ]);
        $brandData = BrandData::from(\IVR\MultiTenancyUtils\Support\StaticTenantData::getBrand());
        expect($faviconData->renderFaviconMetaTags($brandData))
            ->toContain($cdn.'/'.$brandData->key.'/assets/apple-touch-icon.png');
    }

    $legacy = FaviconData::from(['android_theme_color' => '#fff', 'ms_tile_color' => '#fff', 'osx_mask_icon_color' => '#fff']);
    expect($legacy->icons_base_url)->toBeNull();
});

it('uses per-file icons map, with partial coverage falling back to base url then tenant assets', function () {
    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    $cdn = config('multitenancy-utils-laravel.cdn');
    $brandData = BrandData::from(\IVR\MultiTenancyUtils\Support\StaticTenantData::getBrand());
    $colors = ['android_theme_color' => '#fff', 'ms_tile_color' => '#fff', 'osx_mask_icon_color' => '#fff'];

    $full = FaviconData::from($colors + ['icons' => ['favicon.ico' => 'https://media.example.com/1/f.ico']]);
    $tags = $full->renderFaviconMetaTags($brandData);
    expect($tags)->toContain('https://media.example.com/1/f.ico')
        ->toContain($cdn.'/'.$brandData->key.'/assets/apple-touch-icon.png');

    $mixed = FaviconData::from($colors + [
        'icons_base_url' => 'https://base.example.com/i',
        'icons' => ['apple-touch-icon.png' => 'https://media.example.com/2/a.png'],
    ]);
    $tags = $mixed->renderFaviconMetaTags($brandData);
    expect($tags)->toContain('https://media.example.com/2/a.png')
        ->toContain('https://base.example.com/i/favicon.ico')
        ->not->toContain('/assets/');
    expect($mixed->renderWebManifest($brandData)['icons'][0]['src'])->toBe('https://base.example.com/i/android-chrome-192x192.png');
});

it('accepts the icons map decoded as an object, as the API services deliver it', function () {
    $payload = json_decode(json_encode([
        'android_theme_color' => '#dc651f',
        'ms_tile_color' => '#dc651f',
        'osx_mask_icon_color' => '#dc651f',
        'icons' => ['favicon-32x32.png' => 'https://media.example.test/14/favicon-32x32.png'],
    ]));

    $faviconData = FaviconData::from($payload);

    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    $brandData = BrandData::from(\IVR\MultiTenancyUtils\Support\StaticTenantData::getBrand());

    expect($faviconData->renderFaviconMetaTags($brandData))
        ->toContain('https://media.example.test/14/favicon-32x32.png');
});

function otherBrand(array $favicon = []): BrandData
{
    $data = \IVR\MultiTenancyUtils\Support\StaticTenantData::getBrand();
    $data['key'] = 'velletri-va-in-rete';
    $data['name'] = 'Velletri Va In Rete';
    $data['favicon_data'] = $favicon + ['android_theme_color' => '#fff', 'ms_tile_color' => '#fff', 'osx_mask_icon_color' => '#fff'];

    return BrandData::from($data);
}

it('falls back to the CDN assets of the rendered brand, not of the configured tenant', function () {
    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    $cdn = config('multitenancy-utils-laravel.cdn');
    $brand = otherBrand();
    $base = $cdn.'/velletri-va-in-rete/assets';

    expect($brand->renderFaviconMeta())
        ->toContain($base.'/favicon.ico')
        ->toContain($base.'/favicon-32x32.png')
        ->toContain($base.'/apple-touch-icon.png')
        ->toContain($base.'/safari-pinned-tab.svg')
        ->not->toContain('/'.Tenants::IVR_KEY.'/');
    expect($brand->renderBrowserConfigXml())->toContain($base.'/mstile-150x150.png');
    expect($brand->renderWebManifest()['icons'][1]['src'])->toBe($base.'/android-chrome-512x512.png');
});

it('resolves icons map > icons_base_url > brand CDN for a brand other than the configured tenant', function () {
    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    $cdn = config('multitenancy-utils-laravel.cdn');

    $brand = otherBrand([
        'icons_base_url' => 'https://base.example.com/v/',
        'icons' => ['favicon.ico' => 'https://media.example.com/9/f.ico'],
    ]);
    expect($brand->renderFaviconMeta())
        ->toContain('href="https://media.example.com/9/f.ico"')
        ->toContain('https://base.example.com/v/favicon-32x32.png')
        ->not->toContain($cdn);

    $brand = otherBrand(['icons' => ['favicon.ico' => 'https://media.example.com/9/f.ico']]);
    expect($brand->renderFaviconMeta())
        ->toContain('href="https://media.example.com/9/f.ico"')
        ->toContain($cdn.'/velletri-va-in-rete/assets/favicon-32x32.png')
        ->not->toContain('/'.Tenants::IVR_KEY.'/');
});

it('emits the SVG favicon before the PNGs only when the brand declares it', function () {
    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    $cdn = config('multitenancy-utils-laravel.cdn');

    expect(otherBrand()->renderFaviconMeta())->not->toContain('image/svg+xml');

    $tags = otherBrand(['icons' => ['favicon.svg' => 'https://media.example.com/9/f.svg']])->renderFaviconMeta();
    expect($tags)->toContain('<link rel="icon" type="image/svg+xml" href="https://media.example.com/9/f.svg">');
    expect(strpos($tags, 'image/svg+xml'))->toBeLessThan(strpos($tags, 'image/png'));

    $tags = otherBrand(['has_svg_icon' => true])->renderFaviconMeta();
    expect($tags)->toContain('<link rel="icon" type="image/svg+xml" href="'.$cdn.'/velletri-va-in-rete/assets/favicon.svg">');
});

it('escapes brand name, colors and urls in the rendered markup and builds a valid manifest', function () {
    $data = \IVR\MultiTenancyUtils\Support\StaticTenantData::getBrand();
    $data['name'] = 'Bar "Da Mario" <b>&</b>';
    $data['favicon_data'] = [
        'android_theme_color' => '#fff"><script>',
        'ms_tile_color' => '#fff',
        'osx_mask_icon_color' => '#fff',
        'icons' => ['favicon.ico' => 'https://x.test/f.ico?a=1&b="2"'],
    ];
    $brand = BrandData::from($data);

    $tags = $brand->renderFaviconMeta();
    expect($tags)
        ->toContain('content="Bar &quot;Da Mario&quot; &lt;b&gt;&amp;&lt;/b&gt;"')
        ->toContain('content="#fff&quot;&gt;&lt;script&gt;"')
        ->toContain('href="https://x.test/f.ico?a=1&amp;b=&quot;2&quot;"')
        ->not->toContain('<script>');

    expect($brand->renderBrowserConfigXml())->not->toContain('<script>');

    $manifest = $brand->renderWebManifest();
    expect($manifest['name'])->toBe('Bar "Da Mario" <b>&</b>')
        ->and($manifest['theme_color'])->toBe('#fff"><script>');
});
