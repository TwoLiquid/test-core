<?php

namespace App\Transformers\Api\Admin\Order;

use App\Models\MySql\Order\OrderInvoice;
use App\Services\Order\OrderItemService;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemShortTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class OrderInvoiceBuyerTransformer
 *
 * @package App\Transformers\Api\Admin\Order
 */
class OrderInvoiceBuyerTransformer extends BaseTransformer
{
    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * OrderInvoiceBuyerTransformer constructor
     */
    public function __construct()
    {
        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'type',
        'status',
        'items'
    ];

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return array
     */
    public function transform(OrderInvoice $orderInvoice) : array
    {
        return [
            'id'              => $orderInvoice->id,
            'full_id'         => $orderInvoice->full_id,
            'amount_total'    => $this->orderItemService->getAmountTotal($orderInvoice->items),
            'transaction_fee' => null, //TODO: Get transaction fee from transaction
            'items_count'     => count($orderInvoice->items),
            'created_at'      => $orderInvoice->created_at
        ];
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Collection|null
     */
    public function includeItems(OrderInvoice $orderInvoice) : ?Collection
    {
        $invoiceItems = null;

        if ($orderInvoice->relationLoaded('items')) {
            $invoiceItems = $orderInvoice->items;
        }

        return $invoiceItems ? $this->collection($invoiceItems, new OrderItemShortTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeType(OrderInvoice $orderInvoice) : ?Item
    {
        $invoiceType = $orderInvoice->getType();

        return $invoiceType ? $this->item($invoiceType, new InvoiceTypeTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeStatus(OrderInvoice $orderInvoice) : ?Item
    {
        $invoiceStatus = $orderInvoice->getStatus();

        return $invoiceStatus ? $this->item($invoiceStatus, new InvoiceStatusTransformer) : null;
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
