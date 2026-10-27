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
        /** Optional per-brand base URL of the icon files; when empty, the tenant CDN assets path is used. */
        public ?string $icons_base_url = null,
        /** Optional map file name => absolute URL; takes precedence over icons_base_url. */
        public array|\stdClass|null $icons = null,
    )
    {
    }

    private function icon(string $fileName): string
    {
        // The API services json_decode() into objects, so the map may arrive as stdClass.
        $icons = (array) $this->icons;

        if (filled($icons[$fileName] ?? null)) {
            return $icons[$fileName];
        }

        if (filled($this->icons_base_url)) {
            return rtrim($this->icons_base_url, '/') . '/' . ltrim($fileName, '/');
        }

        return MultiTenancyUtils::asset($fileName);
    }

    public function renderBrowserConfigXml(BrandData $brandData): string
    {
        return '<?xml version="1.0" encoding="utf-8"?>
            <browserconfig>
                <msapplication>
                    <tile>
                        <square150x150logo src="'.$this->icon("mstile-150x150.png").'"/>
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
                        "src": "'. $this->icon("android-chrome-192x192.png") . '",
                        "sizes": "192x192",
                        "type": "image/png"
                    },
                    {
                        "src": "'. $this->icon("android-chrome-512x512.png") . '",
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
            <link rel="shortcut icon" href="'.$this->icon("favicon.ico").'">
            <link rel="manifest" href="/site.webmanifest">
            <link rel="apple-touch-icon" sizes="180x180" href="'.$this->icon("apple-touch-icon.png") .'">
            <link rel="icon" type="image/png" sizes="32x32" href="'. $this->icon("favicon-32x32.png") .'">
            <link rel="icon" type="image/png" sizes="16x16" href="'.$this->icon("favicon-16x16.png").'">

            <meta name="msapplication-config" content="/browserconfig.xml">
            <meta name="apple-mobile-web-app-title" content="'.$brandData->name.'">
            <meta name="application-name" content="'.$brandData->name.'">

            <link rel="mask-icon" href="'. $this->icon("safari-pinned-tab.svg") . '" color="'. $this->osx_mask_icon_color . '">
            <meta name="msapplication-TileColor" content="'. $this->ms_tile_color . '">
            <meta name="theme-color" content="'. $this->android_theme_color . '">
        ';
    }
}
