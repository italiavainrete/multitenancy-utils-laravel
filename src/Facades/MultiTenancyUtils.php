<?php

namespace IVR\MultiTenancyUtils\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \IVR\MultiTenancyUtils\MultiTenancyUtils
 */
class MultiTenancyUtils extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \IVR\MultiTenancyUtils\MultiTenancyUtils::class;
    }
}
