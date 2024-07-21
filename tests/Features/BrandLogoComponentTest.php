<?php

use Illuminate\Support\Facades\Http;
use Illuminate\View\ComponentAttributeBag;
use IVR\MultiTenancyUtils\Support\StaticTenantData;
use IVR\MultiTenancyUtils\Views\Components\BrandLogo;
use function Pest\Laravel\assertViewHas;
use function Pest\Laravel\assertViewIs;


it('renders brand logo', function () {
    $brand = StaticTenantData::getBrand();
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => $brand
        ], 200)
    ]);

    $component = new BrandLogo;
    $view = $component->resolveView();

    expect($view->with(['attributes' => new ComponentAttributeBag([])])->render())->toContain($brand['logo']['svgMarkup']);
});

it('renders brand vector logo', function () {
    $circleSvg = '<svg height="100" width="100" xmlns="http://www.w3.org/2000/svg"><circle r="45" cx="50" cy="50" fill="red" /></svg>';

    $brand = StaticTenantData::getBrand();
    $brand['logo']['format'] = \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_RASTER;
    $brand['logo']['imageUrl'] = 'https://images.com/logo.png';
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => $brand
        ], 200)
    ]);

    $component = new BrandLogo;
    $view = $component->resolveView();

    expect($view->with(['attributes' => new ComponentAttributeBag([])])->render())->toContain($brand['logo']['imageUrl']);
});
