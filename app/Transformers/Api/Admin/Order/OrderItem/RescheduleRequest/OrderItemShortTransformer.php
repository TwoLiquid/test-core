<?php

namespace App\Transformers\Api\Admin\Order\OrderItem\RescheduleRequest;

use App\Models\MySql\Order\OrderItem;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemShortTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem\RescheduleRequest
 */
class OrderItemShortTransformer extends BaseTransformer
{
    /**
     * @param OrderItem $orderItem
     *
     * @return array
     */
    public function transform(OrderItem $orderItem) : array
    {
        return [
            'id'      => $orderItem->id,
            'full_id' => $orderItem->full_id
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_items';
    }
}
