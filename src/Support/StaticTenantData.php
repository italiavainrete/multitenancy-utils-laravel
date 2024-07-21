<?php

namespace IVR\MultiTenancyUtils\Support;

class StaticTenantData
{
    static function getBrand(): array
    {
        $data = json_decode(file_get_contents(__DIR__ . '/../../tests/json/brand.json'), true);
        return $data;
    }

    static function getShops(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/../../tests/json/shops.json'), true);
    }

}
