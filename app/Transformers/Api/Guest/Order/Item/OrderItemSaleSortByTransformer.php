<?php

namespace App\Transformers\Api\Guest\Order\Item;

use App\Lists\Order\Item\Sale\SortBy\OrderItemSaleSortByListItem;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemSaleSortByTransformer
 *
 * @package App\Transformers\Api\Guest\Order\Item
 */
class OrderItemSaleSortByTransformer extends BaseTransformer
{
    /**
     * @param OrderItemSaleSortByListItem $orderItemSaleSortByListItem
     *
     * @return array
     */
    public function transform(OrderItemSaleSortByListItem $orderItemSaleSortByListItem) : array
    {
        return [
            'id'   => $orderItemSaleSortByListItem->id,
            'code' => $orderItemSaleSortByListItem->code,
            'name' => $orderItemSaleSortByListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_sale_sort_by';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_sale_sort_by';
    }
}
