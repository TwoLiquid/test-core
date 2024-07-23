<?php

namespace App\Http\Controllers\Api\Guest\Order;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Order\Interfaces\OrderItemRequestStatusControllerInterface;
use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusList;
use App\Transformers\Api\Guest\Order\Item\OrderItemRequestStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class OrderItemRequestStatusController
 *
 * @package App\Http\Controllers\Api\Guest\Order
 */
final class OrderItemRequestStatusController extends BaseController implements OrderItemRequestStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting order item request statuses
         */
        $orderItemRequestStatusListItems = OrderItemRequestStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($orderItemRequestStatusListItems, new OrderItemRequestStatusTransformer),
            trans('validations/api/guest/order/item/request/status/index.result.success')
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
         * Getting order item request status
         */
        $orderItemRequestStatusListItem = OrderItemRequestStatusList::getItem($id);

        /**
         * Checking order item request status existence
         */
        if (!$orderItemRequestStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/order/item/request/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($orderItemRequestStatusListItem, new OrderItemRequestStatusTransformer),
            trans('validations/api/guest/order/item/request/status/show.result.success')
        );
    }
}
