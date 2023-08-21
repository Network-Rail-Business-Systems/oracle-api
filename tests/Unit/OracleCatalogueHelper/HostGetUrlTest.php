<?php

namespace NetworkRailBusinessSystems\OracleApi\Tests\Unit\OracleCatalogueHelper;

use NetworkRailBusinessSystems\OracleApi\OracleCatalogueHelper;
use NetworkRailBusinessSystems\OracleApi\Tests\TestCase;

class HostGetUrlTest extends TestCase
{
    public function testHostUrl(): void
    {
        $this->assertEquals(OracleCatalogueHelper::getBaseUrl(), config('oracle-api.get_url'));
    }
}
