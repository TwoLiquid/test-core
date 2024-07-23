<?php

namespace App\Transformers\Api\Admin\Invoice\Tip\Buyer;

use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class TipInvoiceBuyerListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Tip\Buyer
 */
class TipInvoiceBuyerListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $tipBuyerInvoices;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemStatuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $invoiceStatuses;

    /**
     * InvoiceBuyerListPageTransformer constructor
     *
     * @param EloquentCollection $tipBuyerInvoices
     * @param EloquentCollection $orderItemStatuses,
     * @param EloquentCollection $invoiceStatuses
     */
    public function __construct(
        EloquentCollection $tipBuyerInvoices,
        EloquentCollection $orderItemStatuses,
        EloquentCollection $invoiceStatuses
    )
    {
        $this->tipBuyerInvoices = $tipBuyerInvoices;
        $this->orderItemStatuses = $orderItemStatuses;
        $this->invoiceStatuses = $invoiceStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'order_item_statuses',
        'invoice_statuses',
        'filters',
        'tip_buyer_invoices',
        'vybe_types'
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
        return $this->item([], new TipInvoiceBuyerListPageFilterTransformer(
            $this->orderItemStatuses,
            $this->invoiceStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeTipBuyerInvoices() : ?Collection
    {
        return $this->collection($this->tipBuyerInvoices, new TipInvoiceBuyerListTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeVybeTypes() : ?Collection
    {
        /**
         * Getting vybe types
         */
        $vybeTypes = VybeTypeList::getItems();

        return $this->collection($vybeTypes, new VybeTypeTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tip_buyer_invoice_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tip_buyer_invoice_lists';
    }
}
