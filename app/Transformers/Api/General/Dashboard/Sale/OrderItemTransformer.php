<?php

namespace App\Transformers\Api\General\Dashboard\Sale;

use App\Models\MySql\Order\OrderItem;
use App\Transformers\Api\General\Dashboard\Sale\Request\OrderItemFinishRequestTransformer;
use App\Transformers\Api\General\Dashboard\Sale\Request\OrderItemPendingRequestTransformer;
use App\Transformers\Api\General\Dashboard\Sale\Request\OrderItemRescheduleRequestTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Sale
 */
class OrderItemTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $activityImages;

    /**
     * @var Collection|null
     */
    protected ?Collection $userAvatars;

    /**
     * OrderItemTransformer constructor
     *
     * @param Collection|null $activityImages
     * @param Collection|null $userAvatars
     */
    public function __construct(
        Collection $activityImages = null,
        Collection $userAvatars = null
    )
    {
        /** @var Collection activityImages */
        $this->activityImages = $activityImages;

        /** @var Collection userAvatars */
        $this->userAvatars = $userAvatars;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'status',
        'timeslot',
        'vybe',
        'activity',
        'buyer',
        'appearance',
        'unit',
        'pendingRequest',
        'rescheduleRequest',
        'finishRequest'
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
            'amount_earned' => $orderItem->getTotalAmountEarned()
        ];
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
    public function includeTimeslot(OrderItem $orderItem) : ?Item
    {
        $timeslot = null;

        if ($orderItem->relationLoaded('timeslot')) {
            $timeslot = $orderItem->timeslot;
        }

        return $timeslot ?
            $this->item(
                $timeslot,
                new TimeslotTransformer(
                    $this->userAvatars
                )
            ) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeVybe(OrderItem $orderItem) : ?Item
    {
        $vybe = null;

        if ($orderItem->relationLoaded('vybe')) {
            $vybe = $orderItem->vybe;
        }

        return $vybe ? $this->item($vybe, new VybeTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeActivity(OrderItem $orderItem) : ?Item
    {
        $activity = null;

        if ($orderItem->relationLoaded('vybe')) {
            $vybe = $orderItem->vybe;

            if ($vybe->relationLoaded('activity')) {
                $activity = $vybe->activity;
            }
        }

        return $activity ?
            $this->item(
                $activity,
                new ActivityTransformer(
                    $this->activityImages
                )
            ) : null;
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

        return $buyer ?
            $this->item(
                $buyer,
                new UserTransformer(
                    $this->userAvatars
                )
            ) : null;
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

        return $appearance ? $this->item($appearance, new VybeAppearanceTransformer) : null;
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

        return $unit ? $this->item($unit, new UnitTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includePendingRequest(OrderItem $orderItem) : ?Item
    {
        $pendingRequest = $orderItem->getOpenedPendingRequest();

        return $pendingRequest ? $this->item($pendingRequest, new OrderItemPendingRequestTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeRescheduleRequest(OrderItem $orderItem) : ?Item
    {
        $rescheduleRequest = $orderItem->getOpenedRescheduleRequest();

        return $rescheduleRequest ? $this->item($rescheduleRequest, new OrderItemRescheduleRequestTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeFinishRequest(OrderItem $orderItem) : ?Item
    {
        $finishRequest = $orderItem->getOpenedFinishRequest();

        return $finishRequest ? $this->item($finishRequest, new OrderItemFinishRequestTransformer) : null;
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
