<?php

use Illuminate\Support\Facades\Route;
use IVR\MultiTenancyUtils\Controllers\BrowserConfigXmlController;
use IVR\MultiTenancyUtils\Controllers\WebManifestController;

Route::get('/site.webmanifest', WebManifestController::class)
    ->name('web-manifest');

Route::get('/browserconfig.xml', BrowserConfigXmlController::class)
    ->name('browser-config-xml');
