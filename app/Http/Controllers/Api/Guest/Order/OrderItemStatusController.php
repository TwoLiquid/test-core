<?php

namespace App\Http\Controllers\Api\Guest\Order;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Order\Interfaces\OrderItemStatusControllerInterface;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Transformers\Api\Guest\Order\Item\OrderItemStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class OrderItemStatusController
 *
 * @package App\Http\Controllers\Api\Guest\Order
 */
final class OrderItemStatusController extends BaseController implements OrderItemStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting order item statuses
         */
        $orderItemStatusListItems = OrderItemStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($orderItemStatusListItems, new OrderItemStatusTransformer),
            trans('validations/api/guest/order/item/status/index.result.success')
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
        $orderItemStatusListItem = OrderItemStatusList::getItem($id);

        /**
         * Checking order item status existence
         */
        if (!$orderItemStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/order/item/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($orderItemStatusListItem, new OrderItemStatusTransformer),
            trans('validations/api/guest/order/item/status/show.result.success')
        );
    }
}
