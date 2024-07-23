<?php

namespace App\Transformers\Api\Admin\Invoice\Seller\Pdf;

use App\Models\MySql\Order\OrderInvoice;
use App\Services\Invoice\InvoiceService;
use App\Services\Order\OrderItemService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class InvoiceSellerPdfTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Seller\Pdf
 */
class InvoiceSellerPdfTransformer extends BaseTransformer
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
     * InvoiceSellerPdfTransformer constructor
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
        'credentials',
        'status',
        'seller',
        'method',
        'items',
        'transactions',
        'order'
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
            'subtotal'      => $this->orderItemService->getPriceTotal($orderInvoice->items),
            'amount_total'  => $this->orderItemService->getAmountTotal($orderInvoice->items),
            'handling_fee'  => $this->orderItemService->getTotalHandlingFee($orderInvoice->items),
            'created_at'    => $orderInvoice->created_at ?
                $orderInvoice->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @return Item
     */
    public function includeCredentials() : Item
    {
        return $this->item([], new CompanyCredentialsTransformer);
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
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeOrder(OrderInvoice $orderInvoice) : ?Item
    {
        $order = $orderInvoice->order;

        return $order ? $this->item($order, new OrderTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeSeller(OrderInvoice $orderInvoice) : ?Item
    {
        $seller = null;

        if ($orderInvoice->relationLoaded('items')) {
            $orderItems = $orderInvoice->items;

            if (isset($orderItems[0])) {
                $seller = $orderItems[0]->seller;
            }
        }

        return $seller ? $this->item($seller, new UserTransformer) : null;
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
