<?php

namespace IVR\Sdk\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheFlushCommand extends Command
{
    protected $signature = 'ivr-sdk:cache-flush
        {--scope=all : Cache scope to flush (static|crm|all)}
        {--tenant= : Limit static flush to a single tenant slug}';

    protected $description = 'Flush IVR SDK caches (StaticData / CRM / both).';

    public function handle(): int
    {
        $scope = $this->option('scope') ?? 'all';
        $tenant = $this->option('tenant');
        $store = Cache::store(config('ivr-sdk.cache_store'));

        if (! in_array($scope, ['static', 'crm', 'all'], true)) {
            $this->error("Unknown scope '{$scope}'. Use static|crm|all.");

            return self::INVALID;
        }

        if ($scope === 'static' || $scope === 'all') {
            if ($tenant !== null) {
                $store->forget("ivr-sdk:static:tenant:{$tenant}");
                $store->forget("ivr-sdk:static:shops:{$tenant}");
                $this->info("Flushed static cache for tenant '{$tenant}'.");
            } else {
                $store->forget('ivr-sdk:static:manifest');
                $this->info('Flushed static manifest cache. Per-tenant entries will expire by TTL.');
            }
        }

        if ($scope === 'crm' || $scope === 'all') {
            $this->info('CRM cache entries are flushed by their own keys; no global flush wired in Phase 1.');
        }

        return self::SUCCESS;
    }
}
