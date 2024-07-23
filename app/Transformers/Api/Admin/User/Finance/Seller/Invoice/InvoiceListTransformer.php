<?php

namespace App\Transformers\Api\Admin\User\Finance\Seller\Invoice;

use App\Models\MySql\Order\OrderInvoice;
use App\Services\Order\OrderItemService;
use App\Services\Order\OrderService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class InvoiceListTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Seller\Invoice
 */
class InvoiceListTransformer extends BaseTransformer
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
     * InvoiceListTransformer constructor
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
        'buyer',
        'vybe_types',
        'order_item_statuses',
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
            'id'         => $orderInvoice->id,
            'full_id'    => $orderInvoice->full_id,
            'earned'     => isset($orderInvoice->items[0]) ? $orderInvoice->items[0]->amount_earned : null,
            'created_at' => $orderInvoice->created_at ? $orderInvoice->created_at->format('Y-m-d\TH:i:s.v\Z') : null
        ];
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

        return $buyer ? $this->item($buyer, new UserTransformer) : null;
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
    public function includeOrderItemStatuses(OrderInvoice $orderInvoice) : ?Collection
    {
        $orderItemsStatuses = null;

        if ($orderInvoice->relationLoaded('items')) {
            $orderItemsStatuses = $this->orderItemService->getOrderItemsStatuses(
                $orderInvoice->items
            );
        }

        return $orderItemsStatuses ? $this->collection($orderItemsStatuses, new OrderItemStatusTransformer) : null;
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
