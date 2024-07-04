<?php

use Illuminate\Support\Facades\Http;
use IVR\MultiTenancyUtils\Data\Brand\BrandData;
use IVR\MultiTenancyUtils\MultiTenancyUtils;
use IVR\MultiTenancyUtils\Tests\Support\Utils;

it('returns the correct web manifest', function () {

    config()->set('multitenancy-utils-laravel.tenant_key', 'albano-card');
    // Faking the HTTP response
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => Utils::getTestBrandData()
        ], 200)
    ]);

    // Mocking MultiTenancyUtils::getBrandData() to return the test brand data
    $brandData = BrandData::from(IVR\MultiTenancyUtils\Tests\Support\Utils::getTestBrandData());

    $response = $this->get(route('web-manifest'));

    $cdn = config('multitenancy-utils-laravel.cdn');

    $expectedManifest = [
        "name" => "$brandData->name",
        "short_name" => "$brandData->key",
        "icons" => [
            [
                "src" => "$cdn/favicons/$brandData->key/android-chrome-192x192.png",
                "sizes" => "192x192",
                "type" => "image/png"
            ],
            [
                "src" => "$cdn/favicons/$brandData->key/android-chrome-512x512.png",
                "sizes" => "512x512",
                "type" => "image/png"
            ]
        ],
        "theme_color" => "#ffffff",
        "background_color" => "#ffffff",
        "display" => "standalone"
    ];

    $response->assertStatus(200);
    $response->assertJson($expectedManifest);
});

it('returns the correct browser config XML', function () {
    config()->set('multitenancy-utils-laravel.tenant_key', 'albano-card');
    // Faking the HTTP response
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => Utils::getTestBrandData()
        ], 200)
    ]);

    // Mocking MultiTenancyUtils::getBrandData() to return the test brand data
    $brandData = BrandData::from(IVR\MultiTenancyUtils\Tests\Support\Utils::getTestBrandData());

    $response = $this->get(route('browser-config-xml'));

    $cdn = config('multitenancy-utils-laravel.cdn');

    $expectedXml = '<?xml version="1.0" encoding="utf-8"?>
            <browserconfig>
                <msapplication>
                    <tile>
                        <square150x150logo src="'.$cdn.'/favicons/'.$brandData->key.'/mstile-150x150.png"/>
                        <TileColor>#ffffff</TileColor>
                    </tile>
                </msapplication>
            </browserconfig>';

    $expectedXml = preg_replace('/\s+/', '', $expectedXml);
    $actualXml = preg_replace('/\s+/', '', $response->getContent());

    $response->assertStatus(200);
    expect($actualXml)->toEqual($expectedXml);
});
