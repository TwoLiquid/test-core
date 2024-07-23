<?php

namespace App\Transformers\Api\Admin\Order;

use App\Models\MySql\Order\Order;
use App\Services\Invoice\InvoiceService;
use App\Services\Order\OrderItemService;
use App\Services\Order\OrderService;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemPaymentStatusTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemStatusTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemTransformer;
use App\Transformers\Api\Admin\Order\OrderItem\OrderItemVybeTypeTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class OrderOverviewTransformer
 *
 * @package App\Transformers\Api\Admin\Order
 */
class OrderOverviewTransformer extends BaseTransformer
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
     * @var OrderService
     */
    protected OrderService $orderService;

    /**
     * OrderOverviewTransformer constructor
     */
    public function __construct()
    {
        /** @var InvoiceService invoiceService */
        $this->invoiceService = new InvoiceService();

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var OrderService orderService */
        $this->orderService = new OrderService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'buyer',
        'method',
        'vybe_types',
        'statuses',
        'payment_statuses',
        'items',
        'buyer_invoices',
        'seller_invoices',
        'sales'
    ];

    /**
     * @param Order $order
     *
     * @return array
     */
    public function transform(Order $order) : array
    {
        return [
            'id'           => $order->id,
            'full_id'      => $order->full_id,
            'created_at'   => $order->created_at ? $order->created_at->format('Y-m-d\TH:i:s.v\Z') : null,
            'created_date' => $order->created_at ? $order->created_at->format('d.m.Y') : null,
            'paid_at'      => $order->paid_at ? $order->paid_at->format('Y-m-d\TH:i:s.v\Z') : null,
            'subtotal'     => $order->amount,
            'amount_tax'   => $order->amount_tax,
            'amount_total' => $order->amount_total,
            'handling_fee' => $this->orderService->getItemsTotalHandlingFee($order),
            'payment_fee'  => $order->payment_fee
        ];
    }

    /**
     * @param Order $order
     *
     * @return Item|null
     */
    public function includeBuyer(Order $order) : ?Item
    {
        $buyer = null;

        if ($order->relationLoaded('buyer')) {
            $buyer = $order->buyer;
        }

        return $buyer ? $this->item($buyer, new OrderOverviewBuyerTransformer) : null;
    }

    /**
     * @param Order $order
     *
     * @return Item|null
     */
    public function includeMethod(Order $order) : ?Item
    {
        $paymentMethod = null;

        if ($order->relationLoaded('method')) {
            $paymentMethod = $order->method;
        }

        return $paymentMethod ? $this->item($paymentMethod, new PaymentMethodTransformer) : null;
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

        return $vybeTypes ? $this->collection($vybeTypes, new OrderItemVybeTypeTransformer) : null;
    }

    /**
     * @param Order $order
     *
     * @return Collection|null
     */
    public function includeStatuses(Order $order) : ?Collection
    {
        $orderItemsStatuses = null;

        if ($order->relationLoaded('items')) {
            $orderItemsStatuses = $this->orderItemService->getOrderItemsStatuses(
                $order->items
            );
        }

        return $orderItemsStatuses ? $this->collection($orderItemsStatuses, new OrderItemStatusTransformer) : null;
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
     * @param Order $order
     *
     * @return Collection|null
     */
    public function includeItems(Order $order) : ?Collection
    {
        $items = null;

        if ($order->relationLoaded('items')) {
            $items = $order->items;
        }

        return $items ? $this->collection($items, new OrderItemTransformer) : null;
    }

    /**
     * @param Order $order
     *
     * @return Collection|null
     */
    public function includeBuyerInvoices(Order $order) : ?Collection
    {
        $buyerInvoices = null;

        if ($order->relationLoaded('invoices')) {
            $buyerInvoices = $this->invoiceService->getForBuyerFromOrder(
                $order->invoices
            );
        }

        return $buyerInvoices ? $this->collection($buyerInvoices, new OrderInvoiceBuyerTransformer) : null;
    }

    /**
     * @param Order $order
     *
     * @return Collection|null
     */
    public function includeSellerInvoices(Order $order) : ?Collection
    {
        $sellerInvoices = null;

        if ($order->relationLoaded('invoices')) {
            $sellerInvoices = $this->invoiceService->getForSellerFromOrder(
                $order->invoices
            );
        }

        return $sellerInvoices ? $this->collection($sellerInvoices, new OrderInvoiceSellerTransformer) : null;
    }

    /**
     * @param Order $order
     *
     * @return Collection|null
     */
    public function includeSales(Order $order) : ?Collection
    {
        $sales = null;

        if ($order->relationLoaded('sales')) {
            $sales = $order->sales;
        }

        return $sales ? $this->collection($sales, new OrderOverviewSaleListTransformer) : null;
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
