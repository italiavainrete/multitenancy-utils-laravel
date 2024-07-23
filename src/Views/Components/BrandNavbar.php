<?php

namespace IVR\MultiTenancyUtils\Views\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use IVR\MultiTenancyUtils\Data\UserData;

class BrandNavbar extends Component
{
    public function __construct(
        public UserData $user,
        public string $mode = 'dark'
    ) {}

    public function render(): View
    {
        return view('multi-tenancy::components.brand-navbar', ['user' => $this->user, 'mode' => $this->mode]);
    }
}
