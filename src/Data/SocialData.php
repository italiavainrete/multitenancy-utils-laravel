<?php

namespace IVR\Sdk\Data;

use Spatie\LaravelData\Data;

class SocialData extends Data
{
    public function __construct(
        public string $type,
        public string $link,
    ) {}
}
