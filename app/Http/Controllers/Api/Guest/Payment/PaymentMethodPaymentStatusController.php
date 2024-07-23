<?php

namespace App\Http\Controllers\Api\Guest\Payment;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Payment\Interfaces\PaymentMethodPaymentStatusControllerInterface;
use App\Lists\Payment\Method\Payment\Status\PaymentStatusList;
use App\Transformers\Api\Guest\Payment\Method\Payment\Status\PaymentStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentMethodPaymentStatusController
 *
 * @package App\Http\Controllers\Api\Guest\Payment
 */
final class PaymentMethodPaymentStatusController extends BaseController implements PaymentMethodPaymentStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting payment method payment statuses
         */
        $paymentMethodPaymentStatusListItems = PaymentStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($paymentMethodPaymentStatusListItems, new PaymentStatusTransformer),
            trans('validations/api/guest/payment/method/payment/status/index.result.success')
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
         * Getting payment method payment status
         */
        $paymentMethodPaymentStatusListItem = PaymentStatusList::getItem($id);

        /**
         * Checking payment method payment status existence
         */
        if (!$paymentMethodPaymentStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/payment/method/payment/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($paymentMethodPaymentStatusListItem, new PaymentStatusTransformer),
            trans('validations/api/guest/payment/method/payment/status/show.result.success')
        );
    }
}
