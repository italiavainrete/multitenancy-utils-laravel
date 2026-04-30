<?php

namespace IVR\Sdk\Data;

use Spatie\LaravelData\Data;

class LogoData extends Data
{
    public function __construct(
        public LogoFormat $format,
        public ?string $svgMarkup = null,
        public ?string $imageUrl = null,
    ) {}
}
