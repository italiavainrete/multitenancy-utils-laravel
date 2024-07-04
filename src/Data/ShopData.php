<?php

namespace IVR\MultiTenancyUtils\Data;

use Spatie\LaravelData\Data;

class ShopData extends Data
{
    public function __construct(
        public string   $externalId,
        public string   $name,
        public string   $shop_slug,
        public string   $category,
        public ?string   $category_key,
        public ?int      $rating,
        public ?string   $cashback,
        public ?string   $image,
        public ?string   $address,
        public ?string   $city,
        public ?string   $meta,
        public ?float    $lat,
        public ?float    $long,
        public ?string   $openingHours,
        public ?string   $description,
        public ?string   $zipCode,
        public ?string   $website,
        public ?string   $facebook,
    )
    {
    }
}
