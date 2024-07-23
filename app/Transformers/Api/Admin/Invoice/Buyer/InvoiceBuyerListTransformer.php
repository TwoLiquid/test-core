<?php

namespace App\Transformers\Api\Admin\Invoice\Buyer;

use App\Models\MySql\Order\OrderInvoice;
use App\Services\Order\OrderItemService;
use App\Services\Order\OrderService;
use App\Transformers\Api\Admin\Invoice\Buyer\OrderItem\OrderItemPaymentStatusTransformer;
use App\Transformers\Api\Admin\Invoice\Buyer\OrderItem\OrderItemUserTransformer;
use App\Transformers\Api\Admin\Invoice\Buyer\OrderItem\VybeTypeTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class InvoiceBuyerListTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Buyer
 */
class InvoiceBuyerListTransformer extends BaseTransformer
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
     * InvoiceBuyerListTransformer constructor
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
        'order',
        'buyer',
        'unique_sellers',
        'vybe_types',
        'payment_statuses',
        'status'
    ];

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return array
     */
    public function transform(OrderInvoice $orderInvoice) : array
    {
        return [
            'id'            => $orderInvoice->id,
            'full_id'       => $orderInvoice->full_id,
            'amount_total'  => $this->orderItemService->getAmountTotal($orderInvoice->items),
            'handling_fee'  => $this->orderItemService->getTotalHandlingFee($orderInvoice->items),
            'created_at'    => $orderInvoice->created_at ? $orderInvoice->created_at->format('Y-m-d\TH:i:s.v\Z') : null,
            'sellers_count' => $this->orderService->getUniqueSellersCount($orderInvoice->items)
        ];
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeOrder(OrderInvoice $orderInvoice) : ?Item
    {
        $order = null;

        if ($orderInvoice->relationLoaded('order')) {
            $order = $orderInvoice->order;
        }

        return $order ? $this->item($order, new OrderOverviewShortTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeBuyer(OrderInvoice $orderInvoice) : ?Item
    {
        $buyer = null;

        if ($orderInvoice->relationLoaded('order')) {
            $order = $orderInvoice->order;

            if ($order->relationLoaded('buyer')) {
                $buyer = $order->buyer;
            }
        }

        return $buyer ? $this->item($buyer, new OrderItemUserTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Collection|null
     */
    public function includeUniqueSellers(OrderInvoice $orderInvoice) : ?Collection
    {
        $sellers = null;

        if ($orderInvoice->relationLoaded('items')) {
            $sellers = $this->orderItemService->groupOrderItemsBySellers($orderInvoice->items);
        }

        return $sellers ? $this->collection($sellers, new InvoiceBuyerSellersListTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeFirstSeller(OrderInvoice $orderInvoice) : ?Item
    {
        $seller = null;

        if ($orderInvoice->relationLoaded('items')) {

            if (isset($orderInvoice->items[0])) {
                if ($orderInvoice->items[0]->relationLoaded('appearanceCase')) {
                    $appearanceCase = $orderInvoice->items[0]->appearanceCase;

                    if ($appearanceCase->relationLoaded('vybe')) {
                        $vybe = $appearanceCase->vybe;

                        if ($vybe->relationLoaded('user')) {
                            $seller = $vybe->user;
                        }
                    }
                }
            }
        }

        return $seller ? $this->item($seller, new OrderItemUserTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Collection|null
     */
    public function includeVybeTypes(OrderInvoice $orderInvoice) : ?Collection
    {
        $vybeTypes = null;

        if ($orderInvoice->relationLoaded('items')) {
            $vybeTypes = $this->orderItemService->getVybesTypes(
                $orderInvoice->items
            );
        }

        return $vybeTypes ? $this->collection($vybeTypes, new VybeTypeTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Collection|null
     */
    public function includePaymentStatuses(OrderInvoice $orderInvoice) : ?Collection
    {
        $orderItemsPaymentStatuses = null;

        if ($orderInvoice->relationLoaded('items')) {
            $orderItemsPaymentStatuses = $this->orderItemService->getOrderItemsPaymentStatuses(
                $orderInvoice->items
            );
        }

        return $orderItemsPaymentStatuses ? $this->collection($orderItemsPaymentStatuses, new OrderItemPaymentStatusTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeStatus(OrderInvoice $orderInvoice) : ?Item
    {
        $status = $orderInvoice->getStatus();

        return $status ? $this->item($status, new InvoiceStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'invoice';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'invoices';
    }
}
