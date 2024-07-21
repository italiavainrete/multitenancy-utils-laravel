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
