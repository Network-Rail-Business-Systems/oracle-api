<?php

namespace NetworkRailBusinessSystems\OracleApi\Tests;

use NetworkRailBusinessSystems\OracleApi\OracleApiServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            OracleApiServiceProvider::class,
        ];
    }
}