<?php

namespace App\Http\Controllers\Api\Guest\Payment;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Payment\Interfaces\PaymentMethodFieldTypeControllerInterface;
use App\Lists\Payment\Method\Field\Type\PaymentMethodFieldTypeList;
use App\Transformers\Api\Admin\General\Payment\PaymentMethodFieldTypeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentMethodFieldTypeController
 *
 * @package App\Http\Controllers\Api\Guest\Payment
 */
final class PaymentMethodFieldTypeController extends BaseController implements PaymentMethodFieldTypeControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting a payment method field types
         */
        $paymentMethodFieldTypeListItems = PaymentMethodFieldTypeList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($paymentMethodFieldTypeListItems, new PaymentMethodFieldTypeTransformer),
            trans('validations/api/guest/payment/method/field/type/index.result.success')
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
         * Getting a payment method field type
         */
        $paymentMethodFieldTypeListItem = PaymentMethodFieldTypeList::getItem($id);

        /**
         * Checking a payment method field type existence
         */
        if (!$paymentMethodFieldTypeListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/payment/method/field/type/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($paymentMethodFieldTypeListItem, new PaymentMethodFieldTypeTransformer),
            trans('validations/api/guest/payment/method/field/type/show.result.success')
        );
    }
}
