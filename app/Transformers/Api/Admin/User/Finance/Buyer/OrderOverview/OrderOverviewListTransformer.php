<?php

namespace App\Transformers\Api\Admin\User\Finance\Buyer\OrderOverview;

use App\Models\MySql\Order\Order;
use App\Services\Order\OrderItemService;
use App\Services\Order\OrderService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class OrderOverviewListTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Buyer\OrderOverview
 */
class OrderOverviewListTransformer extends BaseTransformer
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
     * OrderListTransformer constructor
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
        'unique_sellers',
        'vybe_types',
        'payment_statuses'
    ];

    /**
     * @param Order $order
     *
     * @return array
     */
    public function transform(Order $order) : array
    {
        return [
            'id'            => $order->id,
            'full_id'       => $order->full_id,
            'amount'        => $order->amount,
            'created_at'    => $order->created_at ? $order->created_at->format('Y-m-d\TH:i:s.v\Z') : null,
            'sellers_count' => $this->orderService->getUniqueSellersCount($order->items)
        ];
    }

    /**
     * @param Order $order
     *
     * @return Collection|null
     */
    public function includeUniqueSellers(Order $order) : ?Collection
    {
        $sellers = null;

        if ($order->relationLoaded('items')) {
            $sellers = $this->orderService->getUniqueSellers($order->items);
        }

        return $sellers ? $this->collection($sellers, new UserTransformer) : null;
    }

    /**
     * @param Order $order
     *
     * @return Collection|null
     */
    public function includeVybeTypes(Order $order) : ?Collection
    {
        $vybeTypes = null;

        if ($order->relationLoaded('items')) {
            $vybeTypes = $this->orderItemService->getVybesTypes(
                $order->items
            );
        }

        return $vybeTypes ? $this->collection($vybeTypes, new VybeTypeTransformer) : null;
    }

    /**
     * @param Order $order
     *
     * @return Collection|null
     */
    public function includePaymentStatuses(Order $order) : ?Collection
    {
        $orderItemsPaymentStatuses = null;

        if ($order->relationLoaded('items')) {
            $orderItemsPaymentStatuses = $this->orderItemService->getOrderItemsPaymentStatuses(
                $order->items
            );
        }

        return $orderItemsPaymentStatuses ? $this->collection($orderItemsPaymentStatuses, new OrderItemPaymentStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_overview';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_overviews';
    }
}
