<?php

namespace App\Http\Controllers\Api\Guest\Payment;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Payment\Interfaces\PaymentMethodWithdrawalStatusControllerInterface;
use App\Lists\Payment\Method\Withdrawal\Status\PaymentMethodWithdrawalStatusList;
use App\Transformers\Api\Guest\Payment\Method\Withdrawal\Status\PaymentMethodWithdrawalStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentMethodWithdrawalStatusController
 *
 * @package App\Http\Controllers\Api\Guest\Payment
 */
final class PaymentMethodWithdrawalStatusController extends BaseController implements PaymentMethodWithdrawalStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting payment method withdrawal statuses
         */
        $paymentMethodWithdrawalStatusListItems = PaymentMethodWithdrawalStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($paymentMethodWithdrawalStatusListItems, new PaymentMethodWithdrawalStatusTransformer),
            trans('validations/api/guest/payment/method/withdrawal/status/index.result.success')
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
         * Getting payment method withdrawal status
         */
        $paymentMethodWithdrawalStatusListItem = PaymentMethodWithdrawalStatusList::getItem($id);

        /**
         * Checking payment method withdrawal status existence
         */
        if (!$paymentMethodWithdrawalStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/payment/method/withdrawal/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($paymentMethodWithdrawalStatusListItem, new PaymentMethodWithdrawalStatusTransformer),
            trans('validations/api/guest/payment/method/withdrawal/status/show.result.success')
        );
    }
}
