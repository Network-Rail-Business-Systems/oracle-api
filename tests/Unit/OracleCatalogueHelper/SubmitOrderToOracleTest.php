<?php

namespace NetworkRailBusinessSystems\OracleApi\Tests\Unit\OracleCatalogueHelper;

use NetworkRailBusinessSystems\OracleApi\OracleCatalogueHelper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\OracleApi\Tests\TestCase;

class SubmitOrderToOracleTest extends TestCase
{
    protected array $orderDetails;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderDetails = [
            'SalesOrderHeader' => [
                'OrderDate' => '2023-08-21T15:30:00Z',
                'OrderType' => 'Non-Heavy Products',
                'SourceReference' => 1,
                'PlannerId' => 123456,
                'PlannerName' => 'Luna Smith',
                'CostCentre' => '000123',
                'ProjectCode' => 'PROJ01',
                'Status' => 'CREATE',
                'TaskNumber' => 'TASK01',
                'PartyName' => 'Basingstoke',
                'CustomerId' => 306641,
                'CustomerName' => 'HOLDATBASINGSTOKERDC',
                'ShipmentAddress' => [
                    'AddressLine1' => 'HOLDATBASINGSTOKERDC',
                    'AddressLine2' => "Oliver Anderson-01234567890-PRO123",
                    'City' => 'Basingstoke',
                    'County' => 'Basingstoke',
                    'PostalCode' => 'MK9 1EN',
                    'Country' => 'GB',
                ],
                'OrderedForName' => 'Oliver Anderson',
                'License' => [
                    'SafetyCertificate' => 'N',
                    'OffloadFacility' => 'Y',
                    'AccessAvailable' => 'Y',
                ],
            ],
            'SalesOrderLine' => [
                [
                    'ItemNumber' => '0006/123456',
                    'SourceReference' => '1',
                    'Status' => 'CREATE',
                    'AdditionalItemInfo' => '',
                    'Quantity' => 100,
                    'RequiredDate' => '2023-09-21 15:30:00',
                    'ShipmentMethod' => 'DHL',
                    'ShippingInstructions' => 'John Doe - 1234567890 - 123 Main St Suite 456 Cityfake 12345 Countyfake GB',
                    'DeliveryInstructions' => 'It contains fragile electronics that require careful attention',
                    'UnitOfMeasure' => 'Ea',
                ],
            ],
        ];

        Config::set('oracle-api.enabled', false);

        Config::set('oracle-api.url', 'http://fake.test');
        Config::set('oracle-api.username', 'fake-token');
        Config::set('oracle-api.password', 'fake-password');
    }

    public function testSubmitOrderToOracle()
    {
        Http::fake([
            '*' => Http::response(['OrderNumber' => '12345'], 200),
        ]);

        $orderNumber = OracleCatalogueHelper::SubmitOrderToOracle($this->orderDetails);

        $this->assertEquals('12345', $orderNumber);

        $orderDetails = $this->orderDetails;

        // Assert that the correct request was sent
        Http::assertSent(function ($request) use ($orderDetails) {
            return
                $request->header('Content-Type')[0] === 'application/json'
                && $request->header('Accept')[0] === 'application/json'
                && $request->body() === json_encode($orderDetails);
        });
    }
}
