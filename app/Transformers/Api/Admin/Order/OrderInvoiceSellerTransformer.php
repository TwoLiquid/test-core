<?php

namespace App\Transformers\Api\Admin\Order;

use App\Models\MySql\Order\OrderInvoice;
use App\Services\Order\OrderItemService;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemShortTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemUserTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderInvoiceSellerTransformer
 *
 * @package App\Transformers\Api\Admin\Order
 */
class OrderInvoiceSellerTransformer extends BaseTransformer
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
        'first_item',
        'type',
        'status',
        'seller'
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
            'handling_fee'  => $this->orderItemService->getTotalHandlingFee($orderInvoice->items),
            'amount_earned' => $this->orderItemService->getTotalAmountEarned($orderInvoice->items),
            'amount_total'  => $this->orderItemService->getAmountTotal($orderInvoice->items),
            'items_count'   => count($orderInvoice->items),
            'created_at'    => $orderInvoice->created_at
        ];
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeFirstItem(OrderInvoice $orderInvoice) : ?Item
    {
        $invoiceItem = null;

        if ($orderInvoice->relationLoaded('items')) {
            if (isset($orderInvoice->items[0])) {
                $invoiceItem = $orderInvoice->items[0];
            }
        }

        return $invoiceItem ? $this->item($invoiceItem, new OrderItemShortTransformer) : null;
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
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeSeller(OrderInvoice $orderInvoice) : ?Item
    {
        $seller = null;

        if ($orderInvoice->relationLoaded('items')) {
            if (isset($orderInvoice->items[0])) {
                $invoiceItem = $orderInvoice->items[0];

                if ($invoiceItem->relationLoaded('appearanceCase')) {
                    if ($invoiceItem->appearanceCase->relationLoaded('vybe')) {
                        if ($invoiceItem->appearanceCase->vybe->relationLoaded('user')) {
                            $seller = $invoiceItem->appearanceCase->vybe->user;
                        }
                    }
                }
            }
        }

        return $seller ? $this->item($seller, new OrderItemUserTransformer) : null;
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
