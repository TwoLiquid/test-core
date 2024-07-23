<?php

namespace App\Transformers\Api\General\Dashboard\Purchase;

use App\Models\MySql\Order\OrderItem;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class OrderTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Purchase
 */
class OrderTransformer extends BaseTransformer
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
     * OrderTransformer constructor
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
        'items',
        'timeslot',
        'vybe',
        'activity',
        'seller',
        'appearance',
        'unit'
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
            'amount_total' => $orderItem->amount_total
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
    public function includeSeller(OrderItem $orderItem) : ?Item
    {
        $seller = null;

        if ($orderItem->relationLoaded('seller')) {
            $seller = $orderItem->seller;
        }

        return $seller ?
            $this->item(
                $seller,
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
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'orders';
    }
}
