<?php

namespace NetworkRailBusinessSystems\OracleApi\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use NetworkRailBusinessSystems\OracleApi\OracleApiServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'default'])->run();
    }

    protected function getPackageProviders($app): array
    {
        return [
            OracleApiServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'default');
        $app['config']->set('database.connections.default', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}