<?php

namespace IVR\Sdk\Multitenancy;

use Closure;
use Illuminate\Http\Request;
use IVR\Sdk\Clients\Contracts\StaticDataClientContract;
use IVR\Sdk\Multitenancy\Contracts\TenantResolverContract;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantMiddleware
{
    public function __construct(
        private readonly TenantResolverContract $resolver,
        private readonly StaticDataClientContract $staticData,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $slug = $this->resolver->resolveFromHost($request->host())
            ?? config('ivr-sdk.fallback_tenant');

        $tenant = $this->staticData->getTenant($slug);

        app()->instance('tenant', $tenant);
        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}
