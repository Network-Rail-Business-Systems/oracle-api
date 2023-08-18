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
        return config('oracle-api.get_url');
    }

    public static function postBaseUrl(): string
    {
        return config('oracle-api.post_url');
    }

    public static function setWithBasicAuth(): PendingRequest
    {
        return Http::withBasicAuth(
            config('oracle-api.username'),
            config('oracle-api.password'),
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
        if (config('oracle-api.enabled') === true) {
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

        return $catalogCollection->filter(function ($item) {
            return $item['MiniSiteName'] === 'Non-Heavy Products';
        })
            ->mapWithKeys(function ($item) {
                return [
                    $item['ItemCode'] => (object) $item,
                ];
            });
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

    public static function checkPostResponse(?array $response): void
    {
        if ($response === null) {
            throw new ErrorException('Failed to finalise; an unknown error occurred in Oracle.');
        }

        $message = trim($response['errorMessage']);

        if (Str::contains($message, self::COSTCENTRE_ERROR)) {
            throw new ErrorException('Unable to finalise; cost centre is not present in Oracle');
        } elseif (Str::contains($message, self::EMPLOYEE_ERROR)) {
            throw new ErrorException('Unable to finalise; the requestor employee number is not present in Oracle');
        } elseif (Str::contains($message, self::ORDER_EXIST_ERROR)) {
            throw new ErrorException('Unable to finalise; this order already exists in Oracle');
        } elseif (Str::contains($message, self::PROJECT_CODE_ERROR)) {
            throw new ErrorException('Unable to finalise; project code is not present in Oracle');
        } elseif (Str::contains($message, self::TASK_NUMBER_ERROR)) {
            throw new ErrorException('Unable to finalise; task number is not present in Oracle');
        } elseif (Str::contains($message, self::PROJECT_NOT_SYNC)) {
            throw new ErrorException('Unable to finalise; cost centre is not associated with the project code and the expenditure owning organisation');
        } else {
            $message = trim(
                explode('||', $message, 2)[0],
                '|',
            );

            throw new ErrorException("Failed to finalise; $message");
        }
    }
}