<?php

namespace App\Helpers;

use ErrorException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OracleCatalogueHelper
{
    const COSTCENTRE_ERROR = 'Issue while retrieving Cost Centre code';

    const EMPLOYEE_ERROR = 'Issue while retrieving employee For';

    const ORDER_EXIST_ERROR = 'Order Already exists in EBS with this Source Reference';

    const PROJECT_CODE_ERROR = 'Issue while retrieving Project code';

    const PROJECT_NOT_SYNC = 'the flexfield segment Expenditure Owning Org does not exist in the value set';

    const TASK_NUMBER_ERROR = 'Issue while retrieving Project Task Number';

    public static function getBaseUrl(): string
    {
        return config('oracle-catalogue.get_url');
    }

    public static function postBaseUrl(): string
    {
        return config('oracle-catalogue.post_url');
    }

    public static function setWithBasicAuth(): PendingRequest
    {
        return Http::withBasicAuth(
            config('oracle-catalogue.username'),
            config('oracle-catalogue.password'),
        )->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

    public static function search(string $term, int $limit = 100): Collection
    {
        $response = self::setWithBasicAuth()->get(self::getBaseUrl(), [
            'Item' => $term,
            'Description' => $term,
            'Offset' => 0,
            'Limit' => $limit,
        ]);

        self::checkSearchResponseStatus($response->getStatusCode());

        $jsonResponse = $response->json();

        self::checkSearchResponseMessage($jsonResponse['ReturnMessage']);

        return self::searchCatalogItem($jsonResponse['CatalogItem']);
    }

    public static function searchByCode(string $code, int $limit = 100): Collection
    {
        $response = self::setWithBasicAuth()->get(self::getBaseUrl(), [
            'Item' => $code,
            'Offset' => 0,
            'Limit' => $limit,
        ]);

        self::checkSearchResponseStatus($response->getStatusCode());

        $jsonResponse = $response->json();

        self::checkSearchResponseMessage($jsonResponse['ReturnMessage']);

        return self::searchCatalogItem($jsonResponse['CatalogItem']);
    }

    public static function searchByDescription(string $description, int $limit = 100): Collection
    {
        $response = self::setWithBasicAuth()->get(self::getBaseUrl(), [
            'Description' => $description,
            'Offset' => 0,
            'Limit' => $limit,
        ]);

        self::checkSearchResponseStatus($response->getStatusCode());

        $jsonResponse = $response->json();

        self::checkSearchResponseMessage($jsonResponse['ReturnMessage']);

        return self::searchCatalogItem($jsonResponse['CatalogItem']);
    }

    public static function submitOrderToOracle(array $orderDetails): int
    {
        if (config('oracle-catalogue.enabled') === true) {
            return GetOracleCatalogueResponseHelper::generateFakeId();
        }

        $sequenceNumber = SequenceGenerator::generate();

        $response = self::setWithBasicAuth()->post(self::postBaseUrl().$sequenceNumber, $orderDetails);

        $jsonResponse = $response->json();

        if ($response->getStatusCode() !== 200) {
            self::checkPostResponse($jsonResponse);
        }

        return $jsonResponse['OrderNumber'];
    }

    public static function searchCatalogItem(array $catalogItem): Collection
    {
        $catalogCollection = new Collection($catalogItem);

        $catalogItems = $catalogCollection->map(function ($item) {
            return (object) $item;
        });

        return $catalogItems;
    }

    public static function checkSearchResponseStatus(int $status): void
    {
        if ($status !== 200) {
            throw new ErrorException('Failed to get data');
        }
    }

    public static function checkSearchResponseMessage(string $returnMessage): void
    {
        if ($returnMessage !== 'SUCCESS') {
            throw new ErrorException('Item not found');
        }
    }

    public static function checkPostResponse(array $response): void
    {
        $message = $response['errorMessage'];

        $errorMessage = trim($message);

        if (Str::contains($errorMessage, self::COSTCENTRE_ERROR)) {
            throw new ErrorException('Cost centre is invalid');
        } elseif (Str::contains($errorMessage, self::EMPLOYEE_ERROR)) {
            throw new ErrorException('Requestor employee number is invalid');
        } elseif (Str::contains($errorMessage, self::ORDER_EXIST_ERROR)) {
            throw new ErrorException('This order already exists in Oracle');
        } elseif (Str::contains($errorMessage, self::PROJECT_CODE_ERROR)) {
            throw new ErrorException('Project code is invalid');
        } elseif (Str::contains($errorMessage, self::TASK_NUMBER_ERROR)) {
            throw new ErrorException('Task number is invalid');
        } elseif (Str::contains($errorMessage, self::PROJECT_NOT_SYNC)) {
            throw new ErrorException('The task number or project code does not exist in relation to the cost centre.');
        } else {
            throw new ErrorException('Failed to submit; something is wrong with the order data.');
        }
    }
}
