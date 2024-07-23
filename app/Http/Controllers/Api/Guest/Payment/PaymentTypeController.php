<?php

namespace App\Http\Controllers\Api\Guest\Payment;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Payment\Interfaces\PaymentTypeControllerInterface;
use App\Lists\Payment\Type\PaymentTypeList;
use App\Transformers\Api\Guest\Payment\Type\PaymentTypeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentTypeController
 *
 * @package App\Http\Controllers\Api\Guest\Payment
 */
final class PaymentTypeController extends BaseController implements PaymentTypeControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting a payment types
         */
        $paymentTypeListItems = PaymentTypeList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($paymentTypeListItems, new PaymentTypeTransformer),
            trans('validations/api/guest/payment/type/index.result.success')
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
         * Getting a payment type
         */
        $paymentTypeListItem = PaymentTypeList::getItem($id);

        /**
         * Checking payment type existence
         */
        if (!$paymentTypeListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/payment/type/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($paymentTypeListItem, new PaymentTypeTransformer),
            trans('validations/api/guest/payment/type/show.result.success')
        );
    }
}
