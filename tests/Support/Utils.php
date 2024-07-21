<?php

namespace IVR\MultiTenancyUtils\Tests\Support;

class Utils
{
    static function getTestBrandData(): array
    {
        $data = json_decode(file_get_contents(__DIR__ . '/../json/brand.json'), true);
        $data['key'] = 'italia-va-in-rete';
        return $data;
    }

    static function getTestShopsData(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/../json/shops.json'), true);
    }

}
