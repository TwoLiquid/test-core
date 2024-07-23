<?php

namespace App\Http\Controllers\Api\Guest\Currency;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Currency\Interfaces\CurrencyControllerInterface;
use App\Lists\Currency\CurrencyList;
use App\Transformers\Api\Guest\Currency\CurrencyTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class CurrencyController
 *
 * @package App\Http\Controllers\Api\Guest\Currency
 */
final class CurrencyController extends BaseController implements CurrencyControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting currencies
         */
        $currencyListItems = CurrencyList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($currencyListItems, new CurrencyTransformer),
            trans('validations/api/guest/currency/index.result.success')
        );
    }

    /**
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function show(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting currency
         */
        $currencyListItem = CurrencyList::getItem($id);

        /**
         * Checking currency existence
         */
        if (!$currencyListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/currency/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($currencyListItem, new CurrencyTransformer),
            trans('validations/api/guest/currency/show.result.success')
        );
    }
}
