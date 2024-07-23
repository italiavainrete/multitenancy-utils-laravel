<?php

namespace IVR\MultiTenancyUtils;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use IVR\MultiTenancyUtils\Contracts\RetrievesShopsListContract;
use IVR\MultiTenancyUtils\Contracts\RetrievesTenantBrandContract;
use IVR\MultiTenancyUtils\Contracts\RetrievesUserDataContract;
use IVR\MultiTenancyUtils\Services\IvrNetworksApiService;
use IVR\MultiTenancyUtils\Services\UserApiService;
use IVR\MultiTenancyUtils\Views\Components\BrandFavicons;
use IVR\MultiTenancyUtils\Views\Components\BrandLogo;
use IVR\MultiTenancyUtils\Views\Components\BrandNavbar;
use IVR\MultiTenancyUtils\Views\Components\BrandStyle;
use IVR\MultiTenancyUtils\Views\Composers\BrandDataComposer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MultiTenancyUtilsServiceProvider extends PackageServiceProvider
{


    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('multitenancy-utils-laravel')
            ->hasConfigFile()
            ->hasViews('multi-tenancy')
            ->hasViewComponent('multi-tenancy',BrandNavbar::class)
            ->hasViewComponent('multi-tenancy',BrandLogo::class)
            ->hasViewComponent('multi-tenancy',BrandStyle::class)
            ->hasViewComponent('multi-tenancy',BrandFavicons::class)
            ->hasViewComposer('*', BrandDataComposer::class)
            ->hasRoute('web');

    }

    public function packageRegistered()
    {
        $this->app->bind(RetrievesTenantBrandContract::class, IvrNetworksApiService::class);
        $this->app->bind(RetrievesShopsListContract::class, IvrNetworksApiService::class);
        $this->app->bind(RetrievesUserDataContract::class, UserApiService::class);

        $this->app->singleton(
            'tenant',
            fn(Application $app) =>
            $app->make(RetrievesTenantBrandContract::class)
                ->getTenantBrand()
        );
    }

}
