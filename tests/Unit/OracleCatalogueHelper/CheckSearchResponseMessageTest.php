<?php

namespace NetworkRailBusinessSystems\OracleApi\Tests\Unit\OracleCatalogueHelper;

use NetworkRailBusinessSystems\OracleApi\OracleCatalogueHelper;
use ErrorException;
use NetworkRailBusinessSystems\OracleApi\Tests\TestCase;

class CheckSearchResponseMessageTest extends TestCase
{
    public function testCheckGetResponseMessageThrowsException()
    {
        $this->expectException(ErrorException::class);

        $this->expectExceptionMessage('Item not found');

        $returnMessage = 'NO DATA FOUND';

        OracleCatalogueHelper::checkSearchResponseMessage($returnMessage);
    }
}
