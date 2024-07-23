<?php

namespace App\Transformers\Api\Admin\Order;

use App\Models\MySql\Order\OrderItem;
use App\Services\Order\OrderItemService;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemActivityTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemCategoryTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemPaymentStatusTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemStatusTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemTimeslotTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemUnitTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemUserTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemVybeTypeTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemVybeTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemPageTransformer
 *
 * @package App\Transformers\Api\Admin\Order
 */
class OrderItemPageTransformer extends BaseTransformer
{
    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * OrderItemPageTransformer constructor
     */
    public function __construct()
    {
        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'buyer',
        'seller',
        'status',
        'payment_status',
        'vybe_type',
        'unit',
        'category',
        'subcategory',
        'activity',
        'timeslot',
        'order_overview',
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
            'vybe_version' => $orderItem->vybe_version,
            'price'        => $orderItem->price,
            'amount_tax'   => $orderItem->amount_tax,
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

        return $vybeType ? $this->item($vybeType, new OrderItemVybeTypeTransformer) : null;
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
    public function includeUnit(OrderItem $orderItem) : ?Item
    {
        $unit = null;

        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $orderItem->appearanceCase;

            if ($appearanceCase->relationLoaded('unit')) {
                $unit = $appearanceCase->unit;
            }
        }

        return $unit ? $this->item($unit, new OrderItemUnitTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeCategory(OrderItem $orderItem) : ?Item
    {
        $category = null;

        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $orderItem->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                $vybe = $appearanceCase->vybe;

                if ($appearanceCase->relationLoaded('category')) {
                    $category = $vybe->category;
                }
            }
        }

        return $category ? $this->item($category, new OrderItemCategoryTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeSubcategory(OrderItem $orderItem) : ?Item
    {
        $subcategory = null;

        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $orderItem->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                $vybe = $appearanceCase->vybe;

                if ($appearanceCase->relationLoaded('subcategory')) {
                    $subcategory = $vybe->subcategory;
                }
            }
        }

        return $subcategory ? $this->item($subcategory, new OrderItemCategoryTransformer) : null;
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

                if ($appearanceCase->relationLoaded('activity')) {
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
    public function includeOrderOverview(OrderItem $orderItem) : ?Item
    {
        $orderOverview = null;

        if ($orderItem->relationLoaded('order')) {
            $orderOverview = $orderItem->order;
        }

        return $orderOverview ? $this->item($orderOverview, new OrderOverviewShortTransformer) : null;
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
