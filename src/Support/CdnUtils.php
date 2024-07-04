<?php

namespace IVR\MultiTenancyUtils\Support;

use Illuminate\Support\Str;

class CdnUtils
{

    public static function asset(string $fileName)
    {
        $cdnBase = config('multitenancy-utils-laravel.cdn');
        return $cdnBase . Str::start($fileName,'/');
    }
}
