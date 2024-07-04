<?php

namespace IVR\MultiTenancyUtils\Views\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use IVR\MultiTenancyUtils\Enums\SemanticColor;

class BrandStyle extends Component
{
    public function render(): View
    {


        return view('multi-tenancy::components.brand-style');
    }
}
