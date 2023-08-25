# Oracle api.
This api is for searching and ordering iStore catalogue items.

Built for [Laravel 10](https://laravel.com/).

## Installation

Add the library via Composer: `composer require networkrailbusinesssystems/oracle-api`

Once installed, export the config: `php artisan vendor:publish --provider="NetworkRailBusinessSystems\OracleApi\OracleApiServiceProvider.php"`

* Set for accessing the Oracle

```dotenv
    ORACLE_CATALOGUE_USERNAME=
    ORACLE_CATALOGUE_PASSWORD=
    ORACLE_CATALOGUE_EMULATOR=false
```

* Set for Searching by item or description from Oracle.

```dotenv
    ORACLE_CATALOGUE_GET_HOST=
 ```

Then you can use the OracleCatalogueHelper class functions in your project for item search:

```php
use NetworkRailBusinessSystems\OracleApi\OracleCatalogueHelper;

$response = OracleCatalogueHelper::search('016798 or FENCE', limit = 100); // search by item code or description 
$response = OracleCatalogueHelper::searchByCode('0004/016798', limit = 100); // search by item code 
$response = OracleCatalogueHelper::searchByDescription('FENCE', limit = 100);  // search by item description
```

* Set for Order submission.

```dotenv
    ORACLE_CATALOGUE_POST_HOST=
```
Then you can use the OracleCatalogueHelper class function for order submission:

```php
use NetworkRailBusinessSystems\OracleApi\OracleCatalogueHelper;

$oracle_order_number = OracleCatalogueHelper::submitOrderToOracle($orderDetails);
```

The Oracle API package makes use of the [Laravel Http Client](https://laravel.com/docs/10.x/http-client).
This enables you to fake the Http response in your test, so it doesn't need a live connection to the Oracle API.

* Set for fake search response and order submission.

```dotenv
    ORACLE_CATALOGUE_EMULATOR=true
```

```php
Http::fake([
    '*' => Http::response([
            "ItemCode" => "0004/016798",
            "ItemDescription" => "POST FENCE  INTERMDT 6 HOLE",
            "ItemYourPrice" => 50.95,
            "Status" => "NR SUPER",
            "PackSize" => "1",
            "IBECustomAttribute15" => null,
            "ConfigurableItem" => null,
            "ItemPrimaryUOMCode" => null,
            "MiniSiteName" => "Non-Heavy Products"
    ]),
]);
```
