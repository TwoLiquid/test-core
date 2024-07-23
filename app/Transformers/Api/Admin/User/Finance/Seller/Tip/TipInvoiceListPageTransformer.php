<?php

namespace App\Transformers\Api\Admin\User\Finance\Seller\Tip;

use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class TipInvoiceListPageTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Seller\Tip
 */
class TipInvoiceListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $tipSellerInvoices;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemPaymentStatuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $invoiceStatuses;

    /**
     * TipInvoiceListPageTransformer constructor
     *
     * @param EloquentCollection $tipSellerInvoices
     * @param EloquentCollection $orderItemPaymentStatuses,
     * @param EloquentCollection $invoiceStatuses
     */
    public function __construct(
        EloquentCollection $tipSellerInvoices,
        EloquentCollection $orderItemPaymentStatuses,
        EloquentCollection $invoiceStatuses
    )
    {
        $this->tipSellerInvoices = $tipSellerInvoices;
        $this->orderItemPaymentStatuses = $orderItemPaymentStatuses;
        $this->invoiceStatuses = $invoiceStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'order_item_statuses',
        'invoice_statuses',
        'filters',
        'tip_seller_invoices'
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
    public function includeOrderItemStatuses() : ?Collection
    {
        $orderItemStatuses = OrderItemStatusList::getItems();

        return $this->collection($orderItemStatuses, new OrderItemStatusTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeInvoiceStatuses() : ?Collection
    {
        $invoiceStatuses = InvoiceStatusList::getAllForBuyer();

        return $this->collection($invoiceStatuses, new InvoiceStatusTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeFilters() : ?Item
    {
        return $this->item([], new TipInvoiceListPageFilterTransformer(
            $this->orderItemPaymentStatuses,
            $this->invoiceStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeTipSellerInvoices() : ?Collection
    {
        return $this->collection($this->tipSellerInvoices, new TipInvoiceListTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tip_seller_invoice_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tip_seller_invoice_lists';
    }
}
