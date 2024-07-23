<?php

namespace App\Transformers\Api\General\Dashboard\Purchase\Request;

use App\Lists\Order\Item\Request\Initiator\OrderItemRequestInitiatorListItem;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemRequestInitiatorTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Purchase\Request
 */
class OrderItemRequestInitiatorTransformer extends BaseTransformer
{
    /**
     * @param OrderItemRequestInitiatorListItem $orderItemRequestInitiatorListItem
     *
     * @return array
     */
    public function transform(OrderItemRequestInitiatorListItem $orderItemRequestInitiatorListItem) : array
    {
        return [
            'id'    => $orderItemRequestInitiatorListItem->id,
            'code'  => $orderItemRequestInitiatorListItem->code,
            'name'  => $orderItemRequestInitiatorListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_request_initiator';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_request_initiators';
    }
}
