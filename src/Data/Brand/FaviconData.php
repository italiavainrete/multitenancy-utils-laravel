<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

use IVR\MultiTenancyUtils\MultiTenancyUtils;
use Spatie\LaravelData\Data;

class FaviconData extends Data
{

    public function __construct(
        public ?string $android_theme_color,
        public ?string $ms_tile_color,
        public ?string $osx_mask_icon_color,
    )
    {
    }

    public function renderBrowserConfigXml(BrandData $brandData): string
    {
        return '<?xml version="1.0" encoding="utf-8"?>
            <browserconfig>
                <msapplication>
                    <tile>
                        <square150x150logo src="'.MultiTenancyUtils::asset("mstile-150x150.png").'"/>
                        <TileColor>'. $this->ms_tile_color .'</TileColor>
                    </tile>
                </msapplication>
            </browserconfig>';
    }

    public function renderWebManifest(BrandData $brandData): array
    {
        return json_decode('
            {
                "name": "' . $brandData->name . '",
                "short_name": "' . $brandData->key . '",
                "icons": [
                    {
                        "src": "'. MultiTenancyUtils::asset("android-chrome-192x192.png") . '",
                        "sizes": "192x192",
                        "type": "image/png"
                    },
                    {
                        "src": "'. MultiTenancyUtils::asset("android-chrome-512x512.png") . '",
                        "sizes": "512x512",
                        "type": "image/png"
                    }
                ],
                "theme_color": "'. $this->android_theme_color .'",
                "background_color": "' . $this->android_theme_color . '",
                "display": "standalone"
            }', true);
    }

    public function renderFaviconMetaTags(BrandData $brandData): string
    {
        return '
            <link rel="shortcut icon" href="'.MultiTenancyUtils::asset("favicon.ico").'">
            <link rel="manifest" href="/site.webmanifest">
            <link rel="apple-touch-icon" sizes="180x180" href="'.MultiTenancyUtils::asset("apple-touch-icon.png") .'">
            <link rel="icon" type="image/png" sizes="32x32" href="'. MultiTenancyUtils::asset("favicon-32x32.png") .'">
            <link rel="icon" type="image/png" sizes="16x16" href="'.MultiTenancyUtils::asset("favicon-16x16.png").'">

            <meta name="msapplication-config" content="/browserconfig.xml">
            <meta name="apple-mobile-web-app-title" content="'.$brandData->name.'">
            <meta name="application-name" content="'.$brandData->name.'">

            <link rel="mask-icon" href="'. MultiTenancyUtils::asset("safari-pinned-tab.svg") . '" color="'. $this->osx_mask_icon_color . '">
            <meta name="msapplication-TileColor" content="'. $this->ms_tile_color . '">
            <meta name="theme-color" content="'. $this->android_theme_color . '">
        ';
    }
}
