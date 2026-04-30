<?php

namespace IVR\Sdk\Data;

use Spatie\LaravelData\Data;

class ShopData extends Data
{
    public function __construct(
        public string $externalId,
        public string $name,
        public string $shop_slug,
        public string $category,
        public ?string $category_key = null,
        public ?int $rating = null,
        public ?string $cashback = null,
        public ?string $image = null,
        public ?string $address = null,
        public ?string $city = null,
        public ?float $lat = null,
        public ?float $long = null,
        public ?string $openingHours = null,
        public ?string $description = null,
        public ?string $zipCode = null,
        public ?string $website = null,
        public ?string $facebook = null,
        public ?string $instagram = null,
        public ?string $twitter = null,
        public ?string $youtube = null,
    ) {}
}
