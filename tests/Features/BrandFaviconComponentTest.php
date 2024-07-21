<?php

use IVR\MultiTenancyUtils\Constants\Tenants;
use IVR\MultiTenancyUtils\Data\Brand\BrandData;
use IVR\MultiTenancyUtils\Views\Components\BrandFavicons;

it('renders favicon meta tags correctly', function () {
    $cdn = config('multitenancy-utils-laravel.cdn');

    config()->set('multitenancy-utils-laravel.tenant_key', Tenants::IVR_KEY);
    $brandData = BrandData::from(IVR\MultiTenancyUtils\Tests\Support\Utils::getTestBrandData());

    $expectedMetaTags = '
        <link rel="shortcut icon" href="'.$cdn.'/'.$brandData->key.'/assets/favicon.ico">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="apple-touch-icon" sizes="180x180" href="'.$cdn.'/'.$brandData->key.'/assets/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="'.$cdn.'/'.$brandData->key.'/assets/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="'.$cdn.'/'.$brandData->key.'/assets/favicon-16x16.png">

        <meta name="msapplication-config" content="/browserconfig.xml">
        <meta name="apple-mobile-web-app-title" content="'.$brandData->name.'">
        <meta name="application-name" content="'.$brandData->name.'">

        <link rel="mask-icon" href="'.$cdn.'/'.$brandData->key.'/assets/safari-pinned-tab.svg" color="#1f2a6b">
        <meta name="msapplication-TileColor" content="#00aba9">
        <meta name="theme-color" content="#ffffff">
    ';


    $view = (new BrandFavicons)->render()->render();

    $expectedMetaTags = preg_replace('/\s+/', '', $expectedMetaTags);
    $actualMetaTags = preg_replace('/\s+/', '', $view);

    expect($actualMetaTags)->toEqual($expectedMetaTags);
});
