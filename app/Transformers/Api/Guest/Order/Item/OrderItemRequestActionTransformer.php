<?php

namespace App\Transformers\Api\Guest\Order\Item;

use App\Lists\Order\Item\Request\Action\OrderItemRequestActionListItem;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemRequestActionTransformer
 *
 * @package App\Transformers\Api\Guest\Order\Item
 */
class OrderItemRequestActionTransformer extends BaseTransformer
{
    /**
     * @param OrderItemRequestActionListItem $orderItemRequestActionListItem
     *
     * @return array
     */
    public function transform(OrderItemRequestActionListItem $orderItemRequestActionListItem) : array
    {
        return [
            'id'   => $orderItemRequestActionListItem->id,
            'code' => $orderItemRequestActionListItem->code,
            'name' => $orderItemRequestActionListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_request_action';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_request_actions';
    }
}
