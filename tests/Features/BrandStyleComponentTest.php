<?php

use Illuminate\Support\Facades\Http;
use IVR\MultiTenancyUtils\Services\IvrNetworksApiService;
use IVR\MultiTenancyUtils\Support\StaticTenantData;
use IVR\MultiTenancyUtils\Views\Components\BrandStyle;
use function Pest\Laravel\get;
use function Pest\Laravel\assertViewIs;
use function Pest\Laravel\assertViewHas;

it('populates the branding view with the correct brand data', function () {
    // Fake response for the brand API
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => StaticTenantData::getBrand()
        ], 200)
    ]);

    $tenantKey = 'tenant-key';
    $service = new IvrNetworksApiService();
    $brand = $service->getTenantBrand($tenantKey);

    // Define percentages for color variations
    $lightenPercentage = config('multitenancy-utils-laravel.colors.lighten_percentage');
    $darkenPercentage = config('multitenancy-utils-laravel.colors.darken_percentage');

    // Render the view with the brand data
    $view = (new BrandStyle)->render()->render();

    // Check that the CSS variables are present in the rendered view
    expect($view)->toContain("--color-primary: #000000;")
        ->and($view)->toContain("--color-primary-light: " . $brand->colors->primary()->lighten($lightenPercentage) . ";")
        ->and($view)->toContain("--color-primary-dark: " . $brand->colors->primary()->darken($darkenPercentage) . ";")
        ->and($view)->toContain("--color-secondary: #ff6b28;")
        ->and($view)->toContain("--color-secondary-light: " . $brand->colors->secondary()->lighten($lightenPercentage) . ";")
        ->and($view)->toContain("--color-secondary-dark: " . $brand->colors->secondary()->darken($darkenPercentage) . ";")
        ->and($view)->toContain("--color-success: #46a714;")
        ->and($view)->toContain("--color-success-light: " . $brand->colors->success()->lighten($lightenPercentage) . ";")
        ->and($view)->toContain("--color-success-dark: " . $brand->colors->success()->darken($darkenPercentage) . ";")
        ->and($view)->toContain("--color-info: #0ebeef;")
        ->and($view)->toContain("--color-info-light: " . $brand->colors->info()->lighten($lightenPercentage) . ";")
        ->and($view)->toContain("--color-info-dark: " . $brand->colors->info()->darken($darkenPercentage) . ";")
        ->and($view)->toContain("--color-warning: #ffe711;")
        ->and($view)->toContain("--color-warning-light: " . $brand->colors->warning()->lighten($lightenPercentage) . ";")
        ->and($view)->toContain("--color-warning-dark: " . $brand->colors->warning()->darken($darkenPercentage) . ";")
        ->and($view)->toContain("--color-danger: #ff7038;")
        ->and($view)->toContain("--color-danger-light: " . $brand->colors->danger()->lighten($lightenPercentage) . ";")
        ->and($view)->toContain("--color-danger-dark: " . $brand->colors->danger()->darken($darkenPercentage) . ";")
        ->and($view)->toContain('font-family: "Nunito", sans-serif !important;');

    // Check that the themed font is present
});
