<?php

namespace App\Transformers\Api\Guest\Order\Item;

use App\Lists\Order\Item\Purchase\SortBy\OrderItemPurchaseSortByListItem;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemPurchaseSortByTransformer
 *
 * @package App\Transformers\Api\Guest\Order\Item
 */
class OrderItemPurchaseSortByTransformer extends BaseTransformer
{
    /**
     * @param OrderItemPurchaseSortByListItem $orderItemPurchaseSortByListItem
     *
     * @return array
     */
    public function transform(OrderItemPurchaseSortByListItem $orderItemPurchaseSortByListItem) : array
    {
        return [
            'id'   => $orderItemPurchaseSortByListItem->id,
            'code' => $orderItemPurchaseSortByListItem->code,
            'name' => $orderItemPurchaseSortByListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_purchase_sort_by';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_purchase_sort_by';
    }
}
