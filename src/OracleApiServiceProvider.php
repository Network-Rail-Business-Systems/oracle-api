<?php

namespace NetworkRailBusinessSystems\OracleApi;

use Illuminate\Support\ServiceProvider;

class OracleApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/oracle-api-config.php',
            'oracle-api'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/oracle-api-config.php' => config_path('oracle-api.php'),
        ], 'oracle-api');

    }
}