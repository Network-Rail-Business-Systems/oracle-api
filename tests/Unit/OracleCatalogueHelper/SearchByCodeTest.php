<?php

namespace NetworkRailBusinessSystems\OracleApi\Tests\Unit\OracleCatalogueHelper;

use NetworkRailBusinessSystems\OracleApi\OracleCatalogueHelper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use NetworkRailBusinessSystems\OracleApi\Tests\TestCase;

class SearchByCodeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Config::set('oracle-catalogue.url', 'http://fake.test');
        Config::set('oracle-catalogue.username', 'fake-token');
        Config::set('oracle-catalogue.password', 'fake-password');
    }

    public function testSearchByCode()
    {
        $data = file_get_contents(dirname(__DIR__, 2).'/Data/item_search_multiple_items.json');

        Http::fake([
            '*' => Http::response($data),
        ]);

        $response = OracleCatalogueHelper::searchByCode('0004/016798');

        $this->assertNotNull($response);

        $this->assertEquals('0004/016798', $response['0004/016798']->ItemCode);
    }
}
