<?php

namespace App\Transformers\Api\Admin\Invoice\Seller\OrderItem;

use App\Models\MySql\Order\OrderItem;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Seller\OrderItem
 */
class OrderItemTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe_type',
        'timeslot',
        'buyer',
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
            'id'            => $orderItem->id,
            'full_id'       => $orderItem->full_id,
            'amount_total'  => $orderItem->amount_total,
            'handling_fee'  => $orderItem->handling_fee,
            'amount_earned' => $orderItem->amount_earned
        ];
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeVybeType(OrderItem $orderItem) : ?Item
    {
        $vybeType = null;

        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $orderItem->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                $vybeType = $appearanceCase->vybe->getType();
            }
        }

        return $vybeType ? $this->item($vybeType, new VybeTypeTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeTimeslot(OrderItem $orderItem) : ?Item
    {
        $timeslot = null;

        if ($orderItem->relationLoaded('timeslot')) {
            $timeslot = $orderItem->timeslot;
        }

        return $timeslot ? $this->item($timeslot, new OrderItemTimeslotTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeBuyer(OrderItem $orderItem) : ?Item
    {
        $buyer = $orderItem->order->buyer;

        return $buyer ? $this->item($buyer, new OrderItemUserTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeStatus(OrderItem $orderItem) : ?Item
    {
        $orderItemStatus = $orderItem->getStatus();

        return $orderItemStatus ? $this->item($orderItemStatus, new OrderItemStatusTransformer) : null;
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
