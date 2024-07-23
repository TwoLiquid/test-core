<?php

namespace App\Transformers\Api\Admin\Order\OrderItem\PendingRequest;

use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusListItem;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemRequestStatusTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem\PendingRequest
 */
class OrderItemRequestStatusTransformer extends BaseTransformer
{
    /**
     * @param OrderItemRequestStatusListItem $orderItemRequestStatusListItem
     *
     * @return array
     */
    public function transform(OrderItemRequestStatusListItem $orderItemRequestStatusListItem) : array
    {
        return [
            'id'    => $orderItemRequestStatusListItem->id,
            'code'  => $orderItemRequestStatusListItem->code,
            'name'  => $orderItemRequestStatusListItem->name,
            'count' => $orderItemRequestStatusListItem->count
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_request_status';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_request_statuses';
    }
}
