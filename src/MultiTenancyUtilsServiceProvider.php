<?php

namespace IVR\MultiTenancyUtils;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\View;
use IVR\MultiTenancyUtils\Contracts\RetrievesShopsListContract;
use IVR\MultiTenancyUtils\Contracts\RetrievesTenantBrandContract;
use IVR\MultiTenancyUtils\Services\IvrNetworksApiService;
use IVR\MultiTenancyUtils\Views\Branding;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use IVR\MultiTenancyUtils\Commands\MultiTenancyUtilsCommand;

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
            ->hasRoute('web');
    }

}
