<?php

namespace App\Transformers\Api\Admin\Invoice\Buyer;

use App\Models\MySql\Order\OrderInvoice;
use App\Services\Invoice\InvoiceService;
use App\Services\Order\OrderItemService;
use App\Transformers\Api\Admin\Invoice\Buyer\OrderItem\OrderItemPaymentStatusTransformer;
use App\Transformers\Api\Admin\Invoice\Buyer\OrderItem\OrderItemTransformer;
use App\Transformers\Api\Admin\Invoice\Buyer\OrderItem\OrderItemUserTransformer;
use App\Transformers\Api\Admin\Invoice\Buyer\OrderTransaction\OrderTransactionTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class InvoiceBuyerPageTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Buyer
 */
class InvoiceBuyerPageTransformer extends BaseTransformer
{
    /**
     * @var InvoiceService
     */
    protected InvoiceService $invoiceService;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * InvoiceBuyerPageTransformer constructor
     */
    public function __construct()
    {
        /** @var InvoiceService invoiceService */
        $this->invoiceService = new InvoiceService();

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'order',
        'buyer',
        'method',
        'vybe_types',
        'payment_statuses',
        'items',
        'transactions',
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
            'created_at' => $orderInvoice->created_at ?
                $orderInvoice->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
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
     * @return Item|null
     */
    public function includeMethod(OrderInvoice $orderInvoice) : ?Item
    {
        $paymentMethod = null;

        if ($orderInvoice->relationLoaded('order')) {
            $order = $orderInvoice->order;

            if ($order->relationLoaded('method')) {
                $paymentMethod = $order->method;
            }
        }

        return $paymentMethod ? $this->item($paymentMethod, new PaymentMethodTransformer) : null;
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
     * @return Collection|null
     */
    public function includeItems(OrderInvoice $orderInvoice) : ?Collection
    {
        $items = null;

        if ($orderInvoice->relationLoaded('items')) {
            $items = $orderInvoice->items;
        }

        return $items ? $this->collection($items, new OrderItemTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Collection|null
     */
    public function includeTransactions(OrderInvoice $orderInvoice) : ?Collection
    {
        $transactions = null;

        if ($orderInvoice->relationLoaded('transactions')) {
            $transactions = $orderInvoice->transactions;
        }

        return $transactions ? $this->collection($transactions, new OrderTransactionTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeStatus(OrderInvoice $orderInvoice) : ?Item
    {
        $invoiceStatus = $orderInvoice->getStatus();

        return $this->item($invoiceStatus, new InvoiceStatusTransformer);
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
