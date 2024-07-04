<?php

use IVR\MultiTenancyUtils\Support\CdnUtils;

it('returns CDN path with leading slash', function () {
    $filename = '/favicon/favicon.png';
    $path = CdnUtils::asset($filename);
    expect($path)->toEqual(config('multitenancy-utils-laravel.cdn').$filename);
});

it('returns CDN path without leading slash', function () {
    $filename = 'favicon/favicon.png';
    $path = CdnUtils::asset($filename);
    expect($path)->toEqual(config('multitenancy-utils-laravel.cdn')."/$filename");
});
