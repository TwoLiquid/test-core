<?php

namespace App\Transformers\Api\Admin\Order;

use App\Models\MySql\Order\OrderItem;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemVybeTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemPaymentStatusTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemStatusTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemUserTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemListTransformer
 *
 * @package App\Transformers\Api\Admin\Order
 */
class OrderItemListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'buyer',
        'seller',
        'status',
        'payment_status',
        'vybe'
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
            'amount_total' => $orderItem->amount_total,
            'handling_fee' => $orderItem->handling_fee,
            'created_at'   => $orderItem->order->created_at ?
                $orderItem->order->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeBuyer(OrderItem $orderItem) : ?Item
    {
        $buyer = null;

        if ($orderItem->relationLoaded('order')) {
            $order = $orderItem->order;

            if ($order->relationLoaded('buyer')) {
                $buyer = $order->buyer;
            }
        }

        return $buyer ? $this->item($buyer, new OrderItemUserTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeSeller(OrderItem $orderItem) : ?Item
    {
        $seller = null;

        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $orderItem->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                $vybe = $appearanceCase->vybe;

                if ($vybe->relationLoaded('user')) {
                    $seller = $vybe->user;
                }
            }
        }

        return $seller ? $this->item($seller, new OrderItemUserTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeVybe(OrderItem $orderItem) : ?Item
    {
        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $orderItem->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                return $this->item($orderItem, new OrderItemVybeTransformer);
            }
        }

        return null;
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
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includePaymentStatus(OrderItem $orderItem) : ?Item
    {
        $paymentStatus = $orderItem->getPaymentStatus();

        return $paymentStatus ? $this->item($paymentStatus, new OrderItemPaymentStatusTransformer) : null;
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
