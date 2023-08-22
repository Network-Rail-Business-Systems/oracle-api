<?php

namespace NetworkRailBusinessSystems\OracleApi\Tests\Unit\OracleCatalogueHelper;

use ErrorException;
use NetworkRailBusinessSystems\OracleApi\OracleCatalogueHelper;
use NetworkRailBusinessSystems\OracleApi\Tests\TestCase;

class CheckSearchResponseStatusTest extends TestCase
{
    public function testCheckGetResponseThrowsException()
    {
        $this->expectException(ErrorException::class);

        $this->expectExceptionMessage('Failed to get data');

        $status = 401;

        OracleCatalogueHelper::checkSearchResponseStatus($status);
    }
}
