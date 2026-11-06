<?php

namespace IVR\MultiTenancyUtils\Tests\Fixtures;

use Illuminate\Foundation\Auth\User;

class OAuthUser extends User
{
    protected $table = 'users';

    protected $guarded = [];
}
