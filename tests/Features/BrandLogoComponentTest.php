<?php

use Illuminate\Support\Facades\Http;
use Illuminate\View\ComponentAttributeBag;
use IVR\MultiTenancyUtils\Support\StaticTenantData;
use IVR\MultiTenancyUtils\Views\Components\BrandLogo;
use function Pest\Laravel\assertViewHas;
use function Pest\Laravel\assertViewIs;


it('renders brand logo in light mode', function () {
    $brand = StaticTenantData::getBrand();
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => $brand
        ], 200)
    ]);

    $component = new BrandLogo;
    $view = $component->resolveView();

    expect($view->with(['mode' => 'light', 'attributes' => new ComponentAttributeBag([])])->render())->toContain($brand['logo']['imageUrl']);
});

it('renders brand logo in dark mode', function () {
    $brand = StaticTenantData::getBrand();
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => $brand
        ], 200)
    ]);

    $component = new BrandLogo;
    $view = $component->resolveView();

    expect($view->with(['mode' => 'dark', 'attributes' => new ComponentAttributeBag(['mode' => 'light'])])->render())->toContain($brand['logo_dark']['imageUrl']);
});
