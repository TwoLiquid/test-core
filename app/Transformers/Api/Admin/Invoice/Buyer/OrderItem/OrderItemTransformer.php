<?php

namespace App\Transformers\Api\Admin\Invoice\Buyer\OrderItem;

use App\Models\MySql\Order\OrderItem;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Buyer\OrderItem
 */
class OrderItemTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'seller',
        'vybe_type',
        'timeslot',
        'status',
        'payment_status'
    ];

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
            'price'        => $orderItem->price,
            'amount_total' => $orderItem->amount_total,
            'handling_fee' => $orderItem->handling_fee
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
    public function includeSeller(OrderItem $orderItem) : ?Item
    {
        $user = null;

        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $orderItem->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                $vybe = $appearanceCase->vybe;

                if ($vybe->relationLoaded('user')) {
                    $user = $vybe->user;
                }
            }
        }

        return $user ? $this->item($user, new OrderItemUserTransformer) : null;
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
    public function includeStatus(OrderItem $orderItem) : ?Item
    {
        $orderItemStatus = $orderItem->getStatus();

        return $orderItemStatus ? $this->item($orderItemStatus, new OrderItemStatusTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includePaymentStatus(OrderItem $orderItem) : ?Item
    {
        $orderItemPaymentStatus = $orderItem->getPaymentStatus();

        return $orderItemPaymentStatus ? $this->item($orderItemPaymentStatus, new OrderItemPaymentStatusTransformer) : null;
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
