<?php

namespace App\Transformers\Api\Admin\Order;

use App\Models\MySql\Sale;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class SaleOverviewTransformer
 *
 * @package App\Transformers\Api\Admin\Order
 */
class SaleOverviewTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'order',
        'buyer',
        'seller',
        'items'
    ];

    /**
     * @param Sale $sale
     *
     * @return array
     */
    public function transform(Sale $sale) : array
    {
        return [
            'id'            => $sale->id,
            'full_id'       => $sale->full_id,
            'created_at'    => $sale->created_at ? $sale->created_at->format('Y-m-d\TH:i:s.v\Z') : null,
            'created_date'  => $sale->created_at ? $sale->created_at->format('d.m.Y') : null,
            'amount_total'  => $sale->amount_total,
            'handling_fee'  => $sale->handling_fee,
            'amount_earned' => $sale->amount_earned
        ];
    }

    /**
     * @param Sale $sale
     *
     * @return Item|null
     */
    public function includeOrder(Sale $sale) : ?Item
    {
        $order = null;

        if ($sale->relationLoaded('order')) {
            $order = $sale->order;
        }

        return $order ? $this->item($order, new OrderOverviewShortTransformer) : null;
    }

    /**
     * @param Sale $sale
     *
     * @return Item|null
     */
    public function includeBuyer(Sale $sale) : ?Item
    {
        $buyer = null;

        if ($sale->relationLoaded('order')) {
            $order = $sale->order;

            if ($order->relationLoaded('buyer')) {
                $buyer = $sale->order->buyer;
            }
        }

        return $buyer ? $this->item($buyer, new OrderOverviewBuyerTransformer) : null;
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

        return $seller ? $this->item($seller, new OrderOverviewBuyerTransformer) : null;
    }

    /**
     * @param Sale $sale
     *
     * @return Collection|null
     */
    public function includeItems(Sale $sale) : ?Collection
    {
        $items = null;

        if ($sale->relationLoaded('items')) {
            $items = $sale->items;
        }

        return $items ? $this->collection($items, new OrderItemTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'sale_overview';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'sale_overviews';
    }
}
