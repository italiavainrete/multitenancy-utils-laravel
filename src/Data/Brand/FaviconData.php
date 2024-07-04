<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

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

    public function renderBrowserConfigXml(): string
    {
        return '<?xml version="1.0" encoding="utf-8"?>
            <browserconfig>
                <msapplication>
                    <tile>
                        <square150x150logo src="'. app('tenant')->asset('mstile-150x150.png') .'"/>
                        <TileColor>'. $this->ms_tile_color .'</TileColor>
                    </tile>
                </msapplication>
            </browserconfig>';
    }

    public function renderWebManifest(): array
    {
        return json_decode('
            {
                "name": "' . app('tenant')->name . '",
                "short_name": "' . app('tenant')->name . '",
                "icons": [
                    {
                        "src": "'. app('tenant')->asset('android-chrome-192x192.png') . '",
                        "sizes": "192x192",
                        "type": "image/png"
                    },
                    {
                        "src": "'. app('tenant')->asset('android-chrome-512x512.png') . '",
                        "sizes": "512x512",
                        "type": "image/png"
                    }
                ],
                "theme_color": "'. $this->android_theme_color .'",
                "background_color": "' . $this->android_theme_color . '",
                "display": "standalone"
            }', true);
    }

    public function renderFaviconMetaTags(): string
    {
        return '
            <link rel="mask-icon"
                href="'. app('tenant')->asset('safari-pinned-tab.svg') . '"
                color="'. $this->osx_mask_icon_color . '"
            >
            <meta name="msapplication-TileColor" content="'. $this->ms_tile_color . '">
            <meta name="theme-color" content="'. $this->android_theme_color . '">
        ';
    }
}
