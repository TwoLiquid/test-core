<?php

namespace App\Transformers\Api\Admin\User\Finance\Seller\OrderItem;

use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemListPageTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Seller\OrderItem
 */
class OrderItemListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItems;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybeTypes;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemStatuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemPaymentStatuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $invoiceStatuses;

    /**
     * OrderItemListPageTransformer constructor
     *
     * @param EloquentCollection $orderItems
     * @param EloquentCollection $vybeTypes
     * @param EloquentCollection $orderItemStatuses
     * @param EloquentCollection $orderItemPaymentStatuses
     * @param EloquentCollection $invoiceStatuses
     */
    public function __construct(
        EloquentCollection $orderItems,
        EloquentCollection $vybeTypes,
        EloquentCollection $orderItemStatuses,
        EloquentCollection $orderItemPaymentStatuses,
        EloquentCollection $invoiceStatuses
    )
    {
        $this->orderItems = $orderItems;
        $this->vybeTypes = $vybeTypes;
        $this->orderItemStatuses = $orderItemStatuses;
        $this->orderItemPaymentStatuses = $orderItemPaymentStatuses;
        $this->invoiceStatuses = $invoiceStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe_types',
        'order_item_statuses',
        'order_item_payment_statuses',
        'invoice_statuses',
        'filters',
        'order_items'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection|null
     */
    public function includeVybeTypes() : ?Collection
    {
        $vybeTypes = VybeTypeList::getItems();

        return $this->collection($vybeTypes, new VybeTypeTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeOrderItemStatuses() : ?Collection
    {
        $orderItemStatuses = OrderItemStatusList::getItems();

        return $this->collection($orderItemStatuses, new OrderItemStatusTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeOrderItemPaymentStatuses() : ?Collection
    {
        $orderItemPaymentStatuses = OrderItemPaymentStatusList::getItems();

        return $this->collection($orderItemPaymentStatuses, new OrderItemPaymentStatusTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeInvoiceStatuses() : ?Collection
    {
        $invoiceStatuses = InvoiceStatusList::getAllForSeller();

        return $this->collection($invoiceStatuses, new InvoiceStatusTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeFilters() : ?Item
    {
        return $this->item([], new OrderItemListPageFilterTransformer(
            $this->vybeTypes,
            $this->orderItemStatuses,
            $this->orderItemPaymentStatuses,
            $this->invoiceStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeOrderItems() : ?Collection
    {
        return $this->collection($this->orderItems, new OrderItemListTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'order_item_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'order_item_lists';
    }
}
