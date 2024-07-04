<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

use IVR\MultiTenancyUtils\Enums\LogoFormat;
use Spatie\LaravelData\Data;

class LogoData extends Data
{

    public function __construct(
        public LogoFormat  $format,
        public ?string $svgMarkup,
        public ?string $imageUrl
    )
    {
    }
}
