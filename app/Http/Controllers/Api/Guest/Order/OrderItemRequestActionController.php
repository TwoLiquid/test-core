<?php

namespace App\Http\Controllers\Api\Guest\Order;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Order\Interfaces\OrderItemRequestActionControllerInterface;
use App\Lists\Order\Item\Request\Action\OrderItemRequestActionList;
use App\Transformers\Api\Guest\Order\Item\OrderItemRequestActionTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class OrderItemRequestActionController
 *
 * @package App\Http\Controllers\Api\Guest\Order
 */
final class OrderItemRequestActionController extends BaseController implements OrderItemRequestActionControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting order item request actions
         */
        $orderItemRequestActionListItems = OrderItemRequestActionList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($orderItemRequestActionListItems, new OrderItemRequestActionTransformer),
            trans('validations/api/guest/order/item/request/action/index.result.success')
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
         * Getting order item request action
         */
        $orderItemRequestActionListItem = OrderItemRequestActionList::getItem($id);

        /**
         * Checking order item request action existence
         */
        if (!$orderItemRequestActionListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/order/item/request/action/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($orderItemRequestActionListItem, new OrderItemRequestActionTransformer),
            trans('validations/api/guest/order/item/request/action/show.result.success')
        );
    }
}
