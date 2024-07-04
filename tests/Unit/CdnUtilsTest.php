<?php

use IVR\MultiTenancyUtils\Support\CdnUtils;

it('returns CDN path with leading slash', function () {
    $filename = '/favicon/favicon.png';
    $tenantKey = config('multitenancy-utils-laravel.tenant_key');
    $path = CdnUtils::asset($filename);
    expect($path)->toEqual(config('multitenancy-utils-laravel.cdn'). "/$tenantKey/assets" . $filename);
});

it('returns CDN path without leading slash', function () {
    $filename = 'favicon/favicon.png';
    $tenantKey = config('multitenancy-utils-laravel.tenant_key');
    $path = CdnUtils::asset($filename);
    expect($path)->toEqual(config('multitenancy-utils-laravel.cdn'). "/$tenantKey/assets" . "/$filename");
});
