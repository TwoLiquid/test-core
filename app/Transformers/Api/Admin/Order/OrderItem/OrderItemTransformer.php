<?php

namespace App\Transformers\Api\Admin\Order\OrderItem;

use App\Models\MySql\Order\OrderItem;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem
 */
class OrderItemTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'timeslot',
        'appearance',
        'seller',
        'vybe',
        'status',
        'payment_status',
        'unit',
        'seller_invoices'
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
            'amount_tax'   => $orderItem->amount_tax,
            'amount_total' => $orderItem->amount_total,
            'handling_fee' => $orderItem->handling_fee,
            'vybe_version' => $orderItem->vybe_version
        ];
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
    public function includeActivity(OrderItem $orderItem) : ?Item
    {
        $activity = null;

        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $orderItem->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                $vybe = $appearanceCase->vybe;

                if ($vybe->relationLoaded('activity')) {
                    $activity = $vybe->activity;
                }
            }
        }

        return $activity ? $this->item($activity, new OrderItemActivityTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeAppearance(OrderItem $orderItem) : ?Item
    {
        $appearance = null;

        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearance = $orderItem->appearanceCase->getAppearance();
        }

        return $appearance ? $this->item($appearance, new OrderItemAppearanceTransformer) : null;
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
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeUnit(OrderItem $orderItem) : ?Item
    {
        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $orderItem->appearanceCase;

            if ($appearanceCase->relationLoaded('unit')) {
                return $appearanceCase->unit ? $this->item($appearanceCase->unit, new OrderItemUnitTransformer) : null;
            }
        }

        return null;
    }

    public function includeSellerInvoices(OrderItem $orderItem) : ?Collection
    {
        $invoices = null;
        if ($orderItem->relationLoaded('invoices')) {
            $invoices = $orderItem->invoices;
        }

        return $invoices ? $this->collection($invoices, new OrderItemInvoiceSellerTransformer) : null;

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
