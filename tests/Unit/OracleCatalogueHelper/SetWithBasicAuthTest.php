<?php

namespace NetworkRailBusinessSystems\OracleApi\Tests\Unit\OracleCatalogueHelper;

use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\OracleApi\OracleCatalogueHelper;
use NetworkRailBusinessSystems\OracleApi\Tests\TestCase;

class SetWithBasicAuthTest extends TestCase
{
    public function testAuthenticate(): void
    {
        $this->assertEquals(
            OracleCatalogueHelper::setWithBasicAuth(),

            Http::withBasicAuth(
                config('oracle-api.username'),
                config('oracle-api.password'),
            )->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
        );
    }
}
