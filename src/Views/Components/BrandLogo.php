<?php

namespace IVR\MultiTenancyUtils\Views\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BrandLogo extends Component
{
    public function render(): View
    {
        return view('multi-tenancy::components.brand-logo');
    }
}
