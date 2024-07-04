<?php

use Illuminate\Support\Facades\Http;
use IVR\MultiTenancyUtils\Services\IvrNetworksApiService;
use function Pest\Laravel\get;
use function Pest\Laravel\assertViewIs;
use function Pest\Laravel\assertViewHas;

it('populates the branding view with the correct brand data', function () {
    // Fake response for the brand API
    Http::fake([
        '*/api/networks/*/brand' => Http::response([
            'data' => json_decode(file_get_contents(__DIR__ . '/../json/brand.json'))
        ], 200)
    ]);

    $tenantKey = 'tenant-key';
    $service = new IvrNetworksApiService();
    $brand = $service->getTenantBrand($tenantKey);

    // Define percentages for color variations
    $lightenPercentage = config('multitenancy-utils-laravel.colors.lighten_percentage');
    $darkenPercentage = config('multitenancy-utils-laravel.colors.darken_percentage');

    // Render the view with the brand data
    $view = view('multi-tenancy::branding', ['brand' => $brand])->render();

    // Check that the CSS variables are present in the rendered view
    expect($view)->toContain("--color-primary: #252b3c;");
    expect($view)->toContain("--color-primary-light: " . $brand->colors->primary()->lighten($lightenPercentage) . ";");
    expect($view)->toContain("--color-primary-dark: " . $brand->colors->primary()->darken($darkenPercentage) . ";");

    expect($view)->toContain("--color-secondary: #f3714a;");
    expect($view)->toContain("--color-secondary-light: " . $brand->colors->secondary()->lighten($lightenPercentage) . ";");
    expect($view)->toContain("--color-secondary-dark: " . $brand->colors->secondary()->darken($darkenPercentage) . ";");

    expect($view)->toContain("--color-success: #18821f;");
    expect($view)->toContain("--color-success-light: " . $brand->colors->success()->lighten($lightenPercentage) . ";");
    expect($view)->toContain("--color-success-dark: " . $brand->colors->success()->darken($darkenPercentage) . ";");

    expect($view)->toContain("--color-info: #007e89;");
    expect($view)->toContain("--color-info-light: " . $brand->colors->info()->lighten($lightenPercentage) . ";");
    expect($view)->toContain("--color-info-dark: " . $brand->colors->info()->darken($darkenPercentage) . ";");

    expect($view)->toContain("--color-warning: #c48d01;");
    expect($view)->toContain("--color-warning-light: " . $brand->colors->warning()->lighten($lightenPercentage) . ";");
    expect($view)->toContain("--color-warning-dark: " . $brand->colors->warning()->darken($darkenPercentage) . ";");

    expect($view)->toContain("--color-danger: #8c1546;");
    expect($view)->toContain("--color-danger-light: " . $brand->colors->danger()->lighten($lightenPercentage) . ";");
    expect($view)->toContain("--color-danger-dark: " . $brand->colors->danger()->darken($darkenPercentage) . ";");

    // Check that the themed font is present
    expect($view)->toContain('font-family: "Poppins", sans-serif !important;');
});
