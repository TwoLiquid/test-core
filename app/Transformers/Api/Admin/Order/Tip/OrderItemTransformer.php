<?php

namespace App\Transformers\Api\Admin\Order\Tip;

use App\Models\MySql\Order\OrderItem;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemTransformer
 *
 * @package App\Transformers\Api\Admin\Order\Tip
 */
class OrderItemTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'status'
    ];

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
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeStatus(OrderItem $orderItem) : ?Item
    {
        $status = $orderItem->getStatus();

        return $status ? $this->item($status, new OrderItemStatusTransformer) : null;
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
