<?php

namespace App\Transformers\Api\Admin\Invoice\Seller;

use App\Models\MySql\Order\OrderInvoice;
use App\Services\Invoice\InvoiceService;
use App\Services\Order\OrderItemService;
use App\Transformers\Api\Admin\Invoice\Seller\OrderItem\OrderItemStatusTransformer;
use App\Transformers\Api\Admin\Invoice\Seller\OrderItem\OrderItemTransformer;
use App\Transformers\Api\Admin\Invoice\Seller\OrderItem\OrderItemUserTransformer;
use App\Transformers\Api\Admin\Invoice\Seller\OrderTransaction\OrderTransactionTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class InvoiceSellerPageTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Seller
 */
class InvoiceSellerPageTransformer extends BaseTransformer
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
     * InvoiceSellerPageTransformer constructor
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
        'seller',
        'vybe_type',
        'order_item_status',
        'invoice_status',
        'items',
        'transactions'
    ];

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return array
     */
    public function transform(OrderInvoice $orderInvoice) : array
    {
        return [
            'id'           => $orderInvoice->id,
            'full_id'      => $orderInvoice->full_id,
            'subtotal'     => $this->orderItemService->getPriceTotal($orderInvoice->items),
            'amount_total' => $this->orderItemService->getAmountTotal($orderInvoice->items),
            'handling_fee' => $this->orderItemService->getTotalHandlingFee($orderInvoice->items)
        ];
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
                if ($orderItems[0]->relationLoaded('appearanceCase')) {
                    $appearanceCase = $orderItems[0]->appearanceCase;

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
     * @return Item|null
     */
    public function includeVybeType(OrderInvoice $orderInvoice) : ?Item
    {
        $vybeType = null;

        if ($orderInvoice->relationLoaded('items')) {
            $orderItems = $orderInvoice->items;

            if (isset($orderItems[0])) {
                if ($orderItems[0]->relationLoaded('appearanceCase')) {
                    $appearanceCase = $orderItems[0]->appearanceCase;

                    if ($appearanceCase->relationLoaded('vybe')) {
                        $vybeType = $appearanceCase->vybe->getType();
                    }
                }
            }
        }

        return $vybeType ? $this->item($vybeType, new VybeTypeTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeOrderItemStatus(OrderInvoice $orderInvoice) : ?Item
    {
        $orderItemsStatus = null;

        if ($orderInvoice->relationLoaded('items')) {
            $orderItems = $orderInvoice->items;

            if (isset($orderItems[0])) {
                $orderItemsStatus = $orderItems[0]->getStatus();
            }
        }

        return $orderItemsStatus ? $this->item($orderItemsStatus, new OrderItemStatusTransformer) : null;
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeInvoiceStatus(OrderInvoice $orderInvoice) : ?Item
    {
        $status = $orderInvoice->getStatus();

        return $status ? $this->item($status, new InvoiceStatusTransformer) : null;
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
