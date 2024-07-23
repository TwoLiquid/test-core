<?php

namespace App\Http\Controllers\Api\Guest\Withdrawal;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Withdrawal\Interfaces\WithdrawalReceiptStatusControllerInterface;
use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusList;
use App\Transformers\Api\Guest\Withdrawal\Receipt\Status\WithdrawalReceiptStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class WithdrawalReceiptStatusController
 *
 * @package App\Http\Controllers\Api\Guest\Withdrawal
 */
final class WithdrawalReceiptStatusController extends BaseController implements WithdrawalReceiptStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting withdrawal receipt statuses
         */
        $withdrawalReceiptStatusListItems = WithdrawalReceiptStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($withdrawalReceiptStatusListItems, new WithdrawalReceiptStatusTransformer),
            trans('validations/api/guest/withdrawal/receipt/status/index.result.success')
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
         * Getting withdrawal receipt status
         */
        $withdrawalReceiptStatusListItem = WithdrawalReceiptStatusList::getItem($id);

        /**
         * Checking withdrawal receipt status existence
         */
        if (!$withdrawalReceiptStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/withdrawal/receipt/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($withdrawalReceiptStatusListItem, new WithdrawalReceiptStatusTransformer),
            trans('validations/api/guest/withdrawal/receipt/status/show.result.success')
        );
    }
}
