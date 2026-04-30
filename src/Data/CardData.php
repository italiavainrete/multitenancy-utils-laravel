<?php

namespace IVR\Sdk\Data;

use Spatie\LaravelData\Data;

class CardData extends Data
{
    public function __construct(
        public string $name,
        public ?string $front = null,
    ) {}
}
