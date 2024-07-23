<?php

namespace App\Http\Controllers\Api\Guest\Order;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Order\Interfaces\OrderItemRequestInitiatorControllerInterface;
use App\Lists\Order\Item\Request\Initiator\OrderItemRequestInitiatorList;
use App\Transformers\Api\Guest\Order\Item\OrderItemRequestInitiatorTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class OrderItemRequestInitiatorController
 *
 * @package App\Http\Controllers\Api\Guest\Order
 */
final class OrderItemRequestInitiatorController extends BaseController implements OrderItemRequestInitiatorControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting order item request initiators
         */
        $orderItemRequestInitiatorListItems = OrderItemRequestInitiatorList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($orderItemRequestInitiatorListItems, new OrderItemRequestInitiatorTransformer),
            trans('validations/api/guest/order/item/request/initiator/index.result.success')
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
         * Getting order item request initiator
         */
        $orderItemRequestInitiatorListItem = OrderItemRequestInitiatorList::getItem($id);

        /**
         * Checking order item request initiator existence
         */
        if (!$orderItemRequestInitiatorListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/order/item/request/initiator/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($orderItemRequestInitiatorListItem, new OrderItemRequestInitiatorTransformer),
            trans('validations/api/guest/order/item/request/initiator/show.result.success')
        );
    }
}
