<?php

namespace App\Http\Controllers\Api\Guest\AddFunds;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\AddFunds\Interfaces\AddFundsReceiptStatusControllerInterface;
use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusList;
use App\Transformers\Api\Guest\AddFunds\Receipt\Status\AddFundsReceiptStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class AddFundsReceiptStatusController
 *
 * @package App\Http\Controllers\Api\Guest\AddFunds
 */
final class AddFundsReceiptStatusController extends BaseController implements AddFundsReceiptStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting add funds receipt statuses
         */
        $addFundsReceiptStatusListItems = AddFundsReceiptStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($addFundsReceiptStatusListItems, new AddFundsReceiptStatusTransformer),
            trans('validations/api/guest/addFunds/receipt/status/index.result.success')
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
         * Getting add funds receipt status
         */
        $addFundsReceiptStatusListItem = AddFundsReceiptStatusList::getItem($id);

        /**
         * Checking add funds receipt status existence
         */
        if (!$addFundsReceiptStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/addFunds/receipt/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($addFundsReceiptStatusListItem, new AddFundsReceiptStatusTransformer),
            trans('validations/api/guest/addFunds/receipt/status/show.result.success')
        );
    }
}
