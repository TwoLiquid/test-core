<?php

namespace App\Transformers\Api\Admin\Invoice\Buyer\Pdf;

use App\Models\MySql\Order\OrderItem;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Buyer\Pdf
 */
class OrderItemTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe_type'
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
            'amount_total' => $orderItem->amount_total
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

        if ($orderItem->relationLoaded('vybe')) {
            $vybeType = $orderItem->vybe->getType();
        }

        return $vybeType ? $this->item($vybeType, new VybeTypeTransformer) : null;
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
