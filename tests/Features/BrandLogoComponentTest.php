<?php

use Illuminate\Support\Facades\Http;
use IVR\MultiTenancyUtils\Data\Brand\BrandData;
use IVR\MultiTenancyUtils\Services\IvrNetworksApiService;
use IVR\MultiTenancyUtils\Tests\Support\Utils;
use IVR\MultiTenancyUtils\Views\Components\BrandLogo;
use function Pest\Laravel\get;
use function Pest\Laravel\assertViewIs;
use function Pest\Laravel\assertViewHas;

it('renders brand raster logo', function () {
    $brand = Utils::getTestBrandData();
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => $brand
        ], 200)
    ]);

    $view = (new BrandLogo)->render();

    expect($view->render())->toContain($brand['logo']['imageUrl']);
});

it('renders brand vector logo', function () {
    $circleSvg = '<svg height="100" width="100" xmlns="http://www.w3.org/2000/svg"><circle r="45" cx="50" cy="50" fill="red" /></svg>';

    $brand = Utils::getTestBrandData();
    $brand['logo']['format'] = \IVR\MultiTenancyUtils\Enums\LogoFormat::FORMAT_VECTOR;
    $brand['logo']['svgMarkup'] = $circleSvg;
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => $brand
        ], 200)
    ]);

    $view = (new BrandLogo)->render();

    expect($view->render())->toContain($circleSvg);
});
