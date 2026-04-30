<?php

namespace IVR\Sdk;

use IVR\Sdk\Clients\Contracts\CrmApiClientContract;
use IVR\Sdk\Clients\Contracts\StaticDataClientContract;
use IVR\Sdk\Clients\Contracts\UserAreaClientContract;
use IVR\Sdk\Clients\CrmApiClient;
use IVR\Sdk\Clients\StaticDataClient;
use IVR\Sdk\Clients\UserAreaClient;
use IVR\Sdk\Console\Commands\CacheFlushCommand;
use IVR\Sdk\Multitenancy\Contracts\TenantResolverContract;
use IVR\Sdk\Multitenancy\TenantResolver;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class IvrSdkServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('ivr-sdk-laravel')
            ->hasConfigFile('ivr-sdk')
            ->hasCommand(CacheFlushCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(StaticDataClientContract::class, StaticDataClient::class);
        $this->app->singleton(CrmApiClientContract::class, CrmApiClient::class);
        $this->app->singleton(UserAreaClientContract::class, UserAreaClient::class);
        $this->app->singleton(TenantResolverContract::class, TenantResolver::class);

        $this->app->singleton('tenant', function ($app) {
            $resolver = $app->make(TenantResolverContract::class);
            $loader = $app->make(StaticDataClientContract::class);

            $slug = $app->runningInConsole()
                ? config('ivr-sdk.tenant_key')
                : ($resolver->resolveFromHost(request()->host())
                    ?? config('ivr-sdk.fallback_tenant'));

            return $loader->getTenant($slug);
        });
    }
}
