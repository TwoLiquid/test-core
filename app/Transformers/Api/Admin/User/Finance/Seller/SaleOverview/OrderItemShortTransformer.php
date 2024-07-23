<?php

namespace App\Transformers\Api\Admin\User\Finance\Seller\SaleOverview;

use App\Models\MySql\Order\OrderItem;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemShortTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Seller\SaleOverview
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
            'id'           => $orderItem->id,
            'full_id'      => $orderItem->full_id,
            'created_date' => $orderItem->created_at ?
                $orderItem->created_at->format('d.m.Y') :
                null
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
