<?php

namespace IVR\MultiTenancyUtils\Data\Brand;

use Spatie\LaravelData\Data;

class ContactsData extends Data
{
    public function __construct(public string $support)
    {
    }
}
