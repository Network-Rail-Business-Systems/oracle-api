<?php

namespace NetworkRailBusinessSystems\OracleApi;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class GetOracleCatalogueResponseHelper
{
    public static function fakeSearch(string $fileName = 'item_search_multiple_items'): Collection
    {
        $file = dirname(__DIR__, 2)."/tests/Data/OracleCatalogue/{$fileName}.json";

        $data = file_get_contents($file);

        Http::fake([
            '*' => Http::response($data),
        ]);

        return OracleCatalogueHelper::search('0004/016798');
    }

    public static function generateFakeId(): int
    {
        return rand(1, 1000);
    }
}
