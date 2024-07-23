<?php

namespace App\Transformers\Api\Admin\User\Finance\Seller\SaleOverview;

use App\Models\MySql\Sale;
use App\Services\Order\OrderItemService;
use App\Services\Order\OrderService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class SaleOverviewListTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Seller\SaleOverview
 */
class SaleOverviewListTransformer extends BaseTransformer
{
    /**
     * @var OrderService
     */
    protected OrderService $orderService;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * SaleOverviewListTransformer constructor
     */
    public function __construct()
    {
        /** @var OrderService orderService */
        $this->orderService = new OrderService();

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe_types',
        'payment_statuses',
        'items',
        'unique_buyers'
    ];

    /**
     * @param Sale $sale
     *
     * @return array
     */
    public function transform(Sale $sale) : array
    {
        return [
            'id'           => $sale->id,
            'full_id'      => $sale->full_id,
            'amount_total' => $sale->amount_total,
            'created_at'   => $sale->created_at ? $sale->created_at->format('Y-m-d\TH:i:s.v\Z') : null,
            'buyers_count' => $this->orderService->getUniqueBuyersCount($sale->items),
            'items_count'  => count($sale->items)
        ];
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

        return $items ? $this->collection($items, new OrderItemShortTransformer) : null;
    }

    /**
     * @param Sale $sale
     *
     * @return Collection|null
     */
    public function includeUniqueBuyers(Sale $sale) : ?Collection
    {
        $buyers = null;

        if ($sale->relationLoaded('items')) {
            $buyers = $this->orderService->getUniqueBuyers($sale->items);
        }

        return $buyers ? $this->collection($buyers, new UserTransformer) : null;
    }

    /**
     * @param Sale $sale
     *
     * @return Collection|null
     */
    public function includeVybeTypes(Sale $sale) : ?Collection
    {
        $vybeTypes = null;

        if ($sale->relationLoaded('items')) {
            $vybeTypes = $this->orderItemService->getVybesTypes(
                $sale->items
            );
        }

        return $vybeTypes ? $this->collection($vybeTypes, new VybeTypeTransformer) : null;
    }

    /**
     * @param Sale $sale
     *
     * @return Collection|null
     */
    public function includePaymentStatuses(Sale $sale) : ?Collection
    {
        $orderItemsPaymentStatuses = null;

        if ($sale->relationLoaded('items')) {
            $orderItemsPaymentStatuses = $this->orderItemService->getOrderItemsPaymentStatuses(
                $sale->items
            );
        }

        return $orderItemsPaymentStatuses ? $this->collection($orderItemsPaymentStatuses, new OrderItemPaymentStatusTransformer) : null;
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
