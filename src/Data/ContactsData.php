<?php

namespace IVR\Sdk\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ContactsData extends Data
{
    public function __construct(
        public string $support,
        /** @var Collection<int, SocialData> */
        public Collection $social,
    ) {}
}
