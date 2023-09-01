<?php

namespace NetworkRailBusinessSystems\OracleApi\Tests\Unit\OracleCatalogueHelper;

use NetworkRailBusinessSystems\OracleApi\OracleCatalogueHelper;
use NetworkRailBusinessSystems\OracleApi\Tests\TestCase;

class HostPostUrlTest extends TestCase
{
    public function testHostUrl(): void
    {
        $this->assertEquals(OracleCatalogueHelper::postBaseUrl(), config('oracle-api.post_url'));
    }
}
