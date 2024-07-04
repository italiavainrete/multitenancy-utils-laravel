<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

use Spatie\LaravelData\Data;

class FontData extends Data
{
    public function __construct(
        public string $family,
        public string $stylesheet
    )
    {
    }
}
