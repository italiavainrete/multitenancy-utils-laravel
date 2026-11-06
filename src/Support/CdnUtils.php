<?php

namespace IVR\MultiTenancyUtils\Support;

use Illuminate\Support\Str;
use function Laravel\Prompts\select;

class CdnUtils
{
    const ASSETS_PATH = 'assets';
    const PRODUCTS_PATH = 'products';
    const SHOPS_PATH = 'shops';

    static function asset(string $fileName): string
    {
        return self::getAssetsPath() . Str::start($fileName, '/');
    }

    static function product(string $fileName): string
    {
        return self::getProductsPath() . Str::start($fileName, '/');
    }

    static function shop(string $fileName): string
    {
        return self::getShopsPath() . Str::start($fileName, '/');
    }

    /**
     * Asset URL under the CDN folder of an explicit tenant/brand key, instead of the
     * deploy's configured tenant_key (needed by multi-tenant deploys serving many brands).
     */
    static function tenantAsset(string $tenantKey, string $fileName): string
    {
        return self::getCdnBaseUrl()
            . Str::start(trim($tenantKey, '/'), '/')
            . Str::start(self::ASSETS_PATH, '/')
            . Str::start($fileName, '/');
    }

    static function getCdnBaseUrl(): string
    {
        return config('multitenancy-utils-laravel.cdn');
    }


    private static function getTenantKey(): mixed
    {
        return config('multitenancy-utils-laravel.tenant_key');
    }


    static function getTenantPath(): string
    {
        return self::getCdnBaseUrl() . Str::start(self::getTenantKey(), '/');
    }

    static function getAssetsPath(): string
    {
        return self::getTenantPath() . Str::start(self::ASSETS_PATH, '/');
    }

    static function getProductsPath(): string
    {
        return self::getTenantPath() . Str::start(self::PRODUCTS_PATH, '/');
    }


    static function getShopsPath(): string
    {
        return self::getTenantPath() . Str::start(self::SHOPS_PATH, '/');
    }
}
