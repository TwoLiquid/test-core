<?php

namespace App\Transformers\Api\Admin\Order;

use App\Models\MySql\Sale;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemUserTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderOverviewSaleListTransformer
 *
 * @package App\Transformers\Api\Admin\Order
 */
class OrderOverviewSaleListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'seller'
    ];

    /**
     * @param Sale $sale
     *
     * @return array
     */
    public function transform(Sale $sale) : array
    {
        return [
            'id'      => $sale->id,
            'full_id' => $sale->full_id
        ];
    }

    /**
     * @param Sale $sale
     *
     * @return Item|null
     */
    public function includeSeller(Sale $sale) : ?Item
    {
        $seller = null;

        if ($sale->relationLoaded('seller')) {
            $seller = $sale->seller;
        }

        return $seller ? $this->item($seller, new OrderItemUserTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'sale';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'sales';
    }
}
