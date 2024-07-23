<?php

namespace App\Http\Controllers\Api\Guest\Order;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Order\Interfaces\OrderItemSaleSortByControllerInterface;
use App\Lists\Order\Item\Sale\SortBy\OrderItemSaleSortByList;
use App\Transformers\Api\Guest\Order\Item\OrderItemSaleSortByTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class OrderItemSortByController
 *
 * @package App\Http\Controllers\Api\Guest\Order
 */
final class OrderItemSaleSortByController extends BaseController implements OrderItemSaleSortByControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting order item sale sort by
         */
        $orderItemSaleSortByListItems = OrderItemSaleSortByList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($orderItemSaleSortByListItems, new OrderItemSaleSortByTransformer),
            trans('validations/api/guest/order/item/sortBy/sale/index.result.success')
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
         * Getting order item sale sort by
         */
        $orderItemSaleSortByListItem = OrderItemSaleSortByList::getItem($id);

        /**
         * Checking order item sale sort by existence
         */
        if (!$orderItemSaleSortByListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/order/item/sortBy/sale/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($orderItemSaleSortByListItem, new OrderItemSaleSortByTransformer),
            trans('validations/api/guest/order/item/sortBy/sale/show.result.success')
        );
    }
}
