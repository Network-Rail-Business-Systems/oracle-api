<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Support\ServiceProvider;

class OracleApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/oracle-catalogue.php',
            'oracle-catalogue'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/oracle-catalogue.php' => config_path('oracle-catalogue.php'),
        ], 'oracle-catalogue');
    }
}