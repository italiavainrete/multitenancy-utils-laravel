<?php

namespace IVR\MultiTenancyUtils\Controllers;

use Illuminate\Routing\Controller;
use IVR\MultiTenancyUtils\MultiTenancyUtils;

class WebManifestController extends Controller
{
    public function __invoke()
    {
        return response()
            ->json(
                MultiTenancyUtils::getBrandData()
                    ->renderWebManifest()
            );
    }
}
