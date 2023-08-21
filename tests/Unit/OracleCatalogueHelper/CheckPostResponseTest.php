<?php

namespace NetworkRailBusinessSystems\OracleApi\Tests\Unit\OracleCatalogueHelper;

use ErrorException;
use NetworkRailBusinessSystems\OracleApi\OracleCatalogueHelper;
use NetworkRailBusinessSystems\OracleApi\Tests\TestCase;

class CheckPostResponseTest extends TestCase
{
    protected int $orderId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderId = 1;
    }

    public function testReturnResponseForCostCentre(): void
    {
        $this->expectException(ErrorException::class);

        $this->expectExceptionMessage('Unable to finalise; cost centre is not present in Oracle');

        $this->makeRequest("Issue while retrieving Cost Centre code : ORA-01403: no data found {$this->orderId}");
    }

    public function testReturnResponseForEmployeeNumber(): void
    {
        $this->expectException(ErrorException::class);

        $this->expectExceptionMessage('Unable to finalise; the requestor employee number is not present in Oracle');

        $this->makeRequest("Issue while retrieving employee For : ORA-01403: no data found {$this->orderId}");
    }

    public function testReturnResponseForOrderExists(): void
    {
        $this->expectException(ErrorException::class);

        $this->expectExceptionMessage('Unable to finalise; this order already exists in Oracle');

        $this->makeRequest("Order Already exists in EBS with this Source Reference: {$this->orderId}");
    }

    public function testReturnResponseForProjectCode(): void
    {
        $this->expectException(ErrorException::class);

        $this->expectExceptionMessage('Unable to finalise; project code is not present in Oracle');

        $this->makeRequest("Issue while retrieving Project code : ORA-01403: no data found {$this->orderId}");
    }

    public function testReturnResponseForTaskNumber(): void
    {
        $this->expectException(ErrorException::class);

        $this->expectExceptionMessage('Unable to finalise; task number is not present in Oracle');

        $this->makeRequest("Issue while retrieving Project Task Number : ORA-01403: no data found {$this->orderId}");
    }

    public function testReturnResponseForProjectNotExistInCostCentre(): void
    {
        $this->expectException(ErrorException::class);

        $this->expectExceptionMessage('Unable to finalise; cost centre is not associated with the project code and the expenditure owning organisation');

        $this->makeRequest('the flexfield segment Expenditure Owning Org does not exist in the value set NR_VALID_OP_EXPENDITURE_OWNING_ORGS2.');
    }

    public function testReturnResponseForOther(): void
    {
        $this->expectException(ErrorException::class);

        $this->expectExceptionMessage('Failed to finalise; A wild goose appeared!');

        $this->makeRequest('A wild goose appeared!');
    }

    public function testCatchesUnknown(): void
    {
        $this->expectException(ErrorException::class);

        $this->expectExceptionMessage('Failed to finalise; an unknown error occurred in Oracle.');

        $this->makeRequest(null);
    }

    public function makeRequest(?string $message): void
    {
        OracleCatalogueHelper::checkPostResponse($message !== null ? ['errorMessage' => $message] : null);
    }
}
