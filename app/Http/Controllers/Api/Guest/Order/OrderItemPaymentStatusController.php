<?php

namespace App\Http\Controllers\Api\Guest\Order;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Order\Interfaces\OrderItemPaymentStatusControllerInterface;
use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusList;
use App\Transformers\Api\Guest\Order\Item\OrderItemPaymentStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class OrderItemPaymentStatusController
 *
 * @package App\Http\Controllers\Api\Guest\Order
 */
final class OrderItemPaymentStatusController extends BaseController implements OrderItemPaymentStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting order item payment statuses
         */
        $orderItemPaymentStatusListItems = OrderItemPaymentStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($orderItemPaymentStatusListItems, new OrderItemPaymentStatusTransformer),
            trans('validations/api/guest/order/item/payment/status/index.result.success')
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
         * Getting order item status
         */
        $orderItemPaymentStatusListItem = OrderItemPaymentStatusList::getItem($id);

        /**
         * Checking order item payment status existence
         */
        if (!$orderItemPaymentStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/order/item/payment/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($orderItemPaymentStatusListItem, new OrderItemPaymentStatusTransformer),
            trans('validations/api/guest/order/item/payment/status/show.result.success')
        );
    }
}
