<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Buyer\Invoice;

use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Order\OrderItem;
use App\Services\Order\OrderItemService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class OrderInvoiceTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Buyer\Invoice
 */
class OrderInvoiceTransformer extends BaseTransformer
{
    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * OrderInvoiceTransformer constructor
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
        'creators',
        'vybe_types',
        'order_item_payment_statuses',
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
            'id'           => $orderInvoice->id,
            'prefix'       => $orderInvoice->getType()->idPrefix,
            'full_id'      => $orderInvoice->full_id,
            'amount_total' => $this->orderItemService->getAmountTotal($orderInvoice->items),
            'items_count'  => count($orderInvoice->items),
            'created_at'   => $orderInvoice->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Collection|null
     */
    public function includeCreators(OrderInvoice $orderInvoice) : ?Collection
    {
        $users = new EloquentCollection();

        if ($orderInvoice->relationLoaded('items')) {

            /** @var OrderItem $orderItem */
            foreach ($orderInvoice->items as $orderItem) {
                if ($orderItem->relationLoaded('seller')) {
                    $users->push(
                        $orderItem->seller
                    );
                }
            }
        }

        return $this->collection(
            $users->unique('id'),
            new UserShortTransformer
        );
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Collection|null
     */
    public function includeVybeTypes(OrderInvoice $orderInvoice) : ?Collection
    {
        $vybeTypes = new EloquentCollection();

        if ($orderInvoice->relationLoaded('items')) {

            /** @var OrderItem $orderItem */
            foreach ($orderInvoice->items as $orderItem) {
                if ($orderItem->relationLoaded('vybe')) {
                    $vybeTypes->push(
                        $orderItem->vybe
                            ->getType()
                    );
                }
            }
        }

        return $this->collection(
            $vybeTypes->unique('id'),
            new VybeTypeTransformer
        );
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Collection|null
     */
    public function includeOrderItemPaymentStatuses(OrderInvoice $orderInvoice) : ?Collection
    {
        $orderItemPaymentStatuses = null;

        if ($orderInvoice->relationLoaded('items')) {
            $orderItemPaymentStatuses = $this->orderItemService->getOrderItemsPaymentStatuses(
                $orderInvoice->items
            );
        }

        return $this->collection($orderItemPaymentStatuses, new OrderItemPaymentStatusTransformer);
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return Item|null
     */
    public function includeInvoiceStatus(OrderInvoice $orderInvoice) : ?Item
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
