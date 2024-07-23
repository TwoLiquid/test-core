<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Tip;

use App\Models\MySql\Tip\Tip;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class TipTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Tip
 */
class TipTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'item',
        'seller'
    ];

    /**
     * @param Tip $tip
     *
     * @return array
     */
    public function transform(Tip $tip) : array
    {
        return [
            'id'            => $tip->id,
            'amount'        => $tip->amount,
            'amount_earned' => $tip->amount_earned,
            'created_at'    => $tip->created_at ?
                $tip->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param Tip $tip
     *
     * @return Item|null
     */
    public function includeItem(Tip $tip) : ?Item
    {
        $orderItem = null;

        if ($tip->relationLoaded('item')) {
            $orderItem = $tip->item;
        }

        return $orderItem ? $this->item($orderItem, new OrderItemTransformer) : null;
    }

    /**
     * @param Tip $tip
     *
     * @return Item|null
     */
    public function includeSeller(Tip $tip) : ?Item
    {
        $seller = null;

        if ($tip->relationLoaded('seller')) {
            $seller = $tip->seller;
        }

        return $seller ? $this->item($seller, new UserShortTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tip';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tips';
    }
}
