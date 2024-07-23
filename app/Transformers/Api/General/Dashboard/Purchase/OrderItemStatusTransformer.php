<?php

namespace App\Transformers\Api\General\Dashboard\Purchase;

use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemStatusTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Purchase
 */
class OrderItemStatusTransformer extends BaseTransformer
{
    /**
     * @param OrderItemStatusListItem $orderItemStatusListItem
     *
     * @return array
     */
    public function transform(OrderItemStatusListItem $orderItemStatusListItem) : array
    {
        return [
            'id'    => $orderItemStatusListItem->id,
            'code'  => $orderItemStatusListItem->code,
            'name'  => $orderItemStatusListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_statuses';
    }
}
