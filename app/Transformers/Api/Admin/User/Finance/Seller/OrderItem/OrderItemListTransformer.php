<?php

namespace App\Transformers\Api\Admin\User\Finance\Seller\OrderItem;

use App\Models\MySql\Order\OrderItem;
use App\Services\Invoice\InvoiceService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemListTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Buyer\Order\OrderItem
 */
class OrderItemListTransformer extends BaseTransformer
{
    /**
     * @var InvoiceService
     */
    protected InvoiceService $invoiceService;

    /**
     * OrderItemListTransformer constructor
     */
    public function __construct()
    {
        /** @var InvoiceService invoiceService */
        $this->invoiceService = new InvoiceService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'buyer',
        'vybe',
        'status',
        'payment_status',
        'invoice_statuses'
    ];

    /**
     * @param OrderItem $orderItem
     *
     * @return array
     */
    public function transform(OrderItem $orderItem) : array
    {
        return [
            'id'           => $orderItem->id,
            'full_id'      => $orderItem->full_id,
            'amount_total' => $orderItem->amount_total,
            'created_at'   => $orderItem->order->created_at ?
                $orderItem->order->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeBuyer(OrderItem $orderItem) : ?Item
    {
        $buyer = null;

        if ($orderItem->relationLoaded('order')) {
            $order = $orderItem->order;

            if ($order->relationLoaded('buyer')) {
                $buyer = $order->buyer;
            }
        }

        return $buyer ? $this->item($buyer, new UserTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeVybe(OrderItem $orderItem) : ?Item
    {
        $vybe = null;

        if ($orderItem->relationLoaded('appearanceCase')) {
            $appearanceCase = $orderItem->appearanceCase;

            if ($appearanceCase->relationLoaded('vybe')) {
                $vybe = $appearanceCase->vybe;
            }
        }

        return $vybe ? $this->item($vybe, new VybeTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includeStatus(OrderItem $orderItem) : ?Item
    {
        $status = $orderItem->getStatus();

        return $status ? $this->item($status, new OrderItemStatusTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Item|null
     */
    public function includePaymentStatus(OrderItem $orderItem) : ?Item
    {
        $paymentStatus = $orderItem->getPaymentStatus();

        return $paymentStatus ? $this->item($paymentStatus, new OrderItemPaymentStatusTransformer) : null;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return Collection|null
     */
    public function includeInvoiceStatuses(OrderItem $orderItem) : ?Collection
    {
        $invoiceStatuses = $this->invoiceService->getUniqueStatusesFromOrderItem(
            $orderItem
        );

        return $this->collection($invoiceStatuses, new InvoiceStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_items';
    }
}
