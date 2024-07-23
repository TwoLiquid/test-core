<?php

namespace App\Transformers\Api\Admin\Invoice\Seller;

use App\Models\MySql\Order\OrderInvoice;
use App\Services\Order\OrderItemService;
use App\Transformers\Api\Admin\Invoice\Seller\OrderItem\OrderItemStatusTransformer;
use App\Transformers\Api\Admin\Invoice\Seller\OrderItem\OrderItemUserTransformer;
use App\Transformers\Api\Admin\Invoice\Seller\OrderItem\OrderItemVybeTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class InvoiceSellerListTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Seller
 */
class InvoiceSellerListTransformer extends BaseTransformer
{
    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * InvoiceSellerListTransformer constructor
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
        'vybe',
        'seller',
        'buyer',
        'vybe_type',
        'order_item_status',
        'invoice_status'
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
            'amount_earned' => $this->orderItemService->getTotalAmountEarned($orderInvoice->items),
            'amount_total'  => $this->orderItemService->getAmountTotal($orderInvoice->items),
            'handling_fee'  => $this->orderItemService->getTotalHandlingFee($orderInvoice->items),
            'created_at'    => $orderInvoice->created_at ? $orderInvoice->created_at->format('Y-m-d\TH:i:s.v\Z') : null
        ];
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeVybe(OrderInvoice $orderInvoice) : ?Item
    {
        if ($orderInvoice->relationLoaded('items')) {
            $orderItems = $orderInvoice->items;

            if (isset($orderItems[0])) {
                return $orderItems[0] ? $this->item($orderItems[0], new OrderItemVybeTransformer) : null;
            }
        }

        return null;
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
