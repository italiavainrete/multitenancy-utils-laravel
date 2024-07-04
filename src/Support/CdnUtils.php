<?php

namespace IVR\MultiTenancyUtils\Support;

use Illuminate\Support\Str;

class CdnUtils
{

    public static function asset(string $fileName)
    {
        $cdnBase = config('multitenancy-utils-laravel.cdn');
        $tenantKey = config('multitenancy-utils-laravel.tenant_key');
        return $cdnBase . "/$tenantKey/assets" . Str::start($fileName,'/');
    }
}
