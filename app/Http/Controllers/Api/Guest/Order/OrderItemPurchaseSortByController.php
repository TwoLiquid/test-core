<?php

namespace App\Http\Controllers\Api\Guest\Order;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Order\Interfaces\OrderItemPurchaseSortByControllerInterface;
use App\Lists\Order\Item\Purchase\SortBy\OrderItemPurchaseSortByList;
use App\Transformers\Api\Guest\Order\Item\OrderItemPurchaseSortByTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class OrderItemPurchaseSortByController
 *
 * @package App\Http\Controllers\Api\Guest\Order
 */
final class OrderItemPurchaseSortByController extends BaseController implements OrderItemPurchaseSortByControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting order item purchase sort by
         */
        $orderItemPurchaseSortByListItems = OrderItemPurchaseSortByList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($orderItemPurchaseSortByListItems, new OrderItemPurchaseSortByTransformer),
            trans('validations/api/guest/order/item/sortBy/purchase/index.result.success')
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
         * Getting order item purchase sort by
         */
        $orderItemPurchaseSortByListItem = OrderItemPurchaseSortByList::getItem($id);

        /**
         * Checking order item purchase sort by existence
         */
        if (!$orderItemPurchaseSortByListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/order/item/sortBy/purchase/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($orderItemPurchaseSortByListItem, new OrderItemPurchaseSortByTransformer),
            trans('validations/api/guest/order/item/sortBy/purchase/show.result.success')
        );
    }
}
