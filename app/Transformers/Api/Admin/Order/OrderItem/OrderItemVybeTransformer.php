<?php

namespace App\Transformers\Api\Admin\Order\OrderItem;

use App\Models\MySql\Order\OrderItem;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemVybeTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem
 */
class OrderItemVybeTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'type',
        'activity'
    ];

    /**
     * @param OrderItem $orderItem
     *
     * @return array
     */
    public function transform(OrderItem $orderItem) : array
    {
        /**
         * Getting order item vybe
         */
        $vybe = $orderItem->appearanceCase->vybe;

        return [
            'id'           => $vybe->id,
            'title'        => $vybe->title,
            'vybe_version' => $orderItem->vybe_version
        ];
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeType(OrderItem $orderItem) : ?Item
    {
        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $orderItem->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                $type = $appearanceCase->vybe->getType();

                return $type ? $this->item($type, new OrderItemVybeTypeTransformer) : null;
            }
        }

        return null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeActivity(OrderItem $orderItem) : ?Item
    {
        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $orderItem->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                $activity = $appearanceCase->vybe->activity;

                return $activity ? $this->item($activity, new OrderItemActivityTransformer) : null;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybes';
    }
}
