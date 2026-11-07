<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

use IVR\MultiTenancyUtils\MultiTenancyUtils;
use IVR\MultiTenancyUtils\Support\CdnUtils;
use Spatie\LaravelData\Data;

class FaviconData extends Data
{
    const SVG_ICON = 'favicon.svg';

    public function __construct(
        public ?string $android_theme_color,
        public ?string $ms_tile_color,
        public ?string $osx_mask_icon_color,
        /** Optional per-brand base URL of the icon files; when empty, the brand CDN assets path is used. */
        public ?string $icons_base_url = null,
        /** Optional map file name => absolute URL; takes precedence over icons_base_url. */
        public array|\stdClass|null $icons = null,
        /**
         * Whether the brand ships a favicon.svg reachable through icons_base_url or the CDN.
         * Not needed when favicon.svg is declared in the icons map.
         */
        public bool $has_svg_icon = false,
    )
    {
    }

    /**
     * Resolution order: icons map > icons_base_url > CDN assets of the brand being rendered.
     * Without a brand key it falls back to the deploy tenant (config tenant_key), as before.
     */
    private function icon(string $fileName, ?string $brandKey = null): string
    {
        // The API services json_decode() into objects, so the map may arrive as stdClass.
        // Read with ?? : objects unserialized from a cache written by an older version of this
        // class lack the newer promoted properties, which stay uninitialized (see hasSvgIcon()).
        $icons = (array) ($this->icons ?? null);
        $iconsBaseUrl = $this->icons_base_url ?? null;

        if (filled($icons[$fileName] ?? null)) {
            return $icons[$fileName];
        }

        if (filled($iconsBaseUrl)) {
            return rtrim($iconsBaseUrl, '/') . '/' . ltrim($fileName, '/');
        }

        if (filled($brandKey)) {
            return CdnUtils::tenantAsset($brandKey, $fileName);
        }

        return MultiTenancyUtils::asset($fileName);
    }

    /**
     * The SVG link is emitted only when the brand declares it: browsers prefer it over the
     * PNGs, so a link to a missing file would leave the tab without a favicon.
     */
    public function hasSvgIcon(): bool
    {
        // BrandData is cached serialized (IvrNetworksApiService, brand:<key>): after an upgrade a cached
        // object has no has_svg_icon/icons, and reading an uninitialized typed property is a fatal Error
        // (500 on the portals on 7/11/2026). ?? tolerates it.
        return ($this->has_svg_icon ?? false) || filled(((array) ($this->icons ?? null))[self::SVG_ICON] ?? null);
    }

    public function renderBrowserConfigXml(BrandData $brandData): string
    {
        return '<?xml version="1.0" encoding="utf-8"?>
            <browserconfig>
                <msapplication>
                    <tile>
                        <square150x150logo src="'.e($this->icon("mstile-150x150.png", $brandData->key)).'"/>
                        <TileColor>'. e($this->ms_tile_color) .'</TileColor>
                    </tile>
                </msapplication>
            </browserconfig>';
    }

    public function renderWebManifest(BrandData $brandData): array
    {
        return [
            'name' => $brandData->name,
            'short_name' => $brandData->key,
            'icons' => [
                [
                    'src' => $this->icon('android-chrome-192x192.png', $brandData->key),
                    'sizes' => '192x192',
                    'type' => 'image/png',
                ],
                [
                    'src' => $this->icon('android-chrome-512x512.png', $brandData->key),
                    'sizes' => '512x512',
                    'type' => 'image/png',
                ],
            ],
            'theme_color' => (string) $this->android_theme_color,
            'background_color' => (string) $this->android_theme_color,
            'display' => 'standalone',
        ];
    }

    public function renderFaviconMetaTags(BrandData $brandData): string
    {
        $icon = fn (string $fileName) => e($this->icon($fileName, $brandData->key));
        $name = e($brandData->name);

        $svg = $this->hasSvgIcon()
            ? '<link rel="icon" type="image/svg+xml" href="'.$icon(self::SVG_ICON).'">'
            : '';

        // Same order as before, with the SVG inserted ahead of the PNGs. favicon.ico stays first in
        // the markup on purpose: browsers pick the last equally suitable icon, so putting it last
        // would let Firefox prefer the .ico over the SVG/PNGs; being first it is only the legacy fallback.
        return '
            <link rel="shortcut icon" href="'.$icon("favicon.ico").'">
            <link rel="manifest" href="/site.webmanifest">
            <link rel="apple-touch-icon" sizes="180x180" href="'.$icon("apple-touch-icon.png").'">
            '.$svg.'
            <link rel="icon" type="image/png" sizes="32x32" href="'.$icon("favicon-32x32.png").'">
            <link rel="icon" type="image/png" sizes="16x16" href="'.$icon("favicon-16x16.png").'">

            <meta name="msapplication-config" content="/browserconfig.xml">
            <meta name="apple-mobile-web-app-title" content="'.$name.'">
            <meta name="application-name" content="'.$name.'">

            <link rel="mask-icon" href="'.$icon("safari-pinned-tab.svg").'" color="'.e($this->osx_mask_icon_color).'">
            <meta name="msapplication-TileColor" content="'.e($this->ms_tile_color).'">
            <meta name="theme-color" content="'.e($this->android_theme_color).'">
        ';
    }
}
