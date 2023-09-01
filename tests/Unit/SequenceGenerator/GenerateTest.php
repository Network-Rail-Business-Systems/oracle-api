<?php

namespace NetworkRailBusinessSystems\OracleApi\Tests\Unit\SequenceGenerator;

use NetworkRailBusinessSystems\OracleApi\SequenceGenerator;
use NetworkRailBusinessSystems\OracleApi\Tests\TestCase;

class GenerateTest extends TestCase
{
    public function testReturnSequenceNumber(): void
    {
        $this->assertIsNumeric(SequenceGenerator::generate());
    }
}
