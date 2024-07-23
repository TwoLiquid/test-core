<?php

namespace App\Transformers\Api\Admin\Invoice\Tip\Seller;

use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class TipInvoiceSellerListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Tip\Seller
 */
class TipInvoiceSellerListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $tipSellerInvoices;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemStatuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $invoiceStatuses;

    /**
     * TipInvoiceSellerListPageTransformer constructor
     *
     * @param EloquentCollection $tipSellerInvoices
     * @param EloquentCollection $orderItemStatuses,
     * @param EloquentCollection $invoiceStatuses
     */
    public function __construct(
        EloquentCollection $tipSellerInvoices,
        EloquentCollection $orderItemStatuses,
        EloquentCollection $invoiceStatuses
    )
    {
        $this->tipSellerInvoices = $tipSellerInvoices;
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
        'tip_seller_invoices',
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
        return $this->item([], new TipInvoiceSellerListPageFilterTransformer(
            $this->orderItemStatuses,
            $this->invoiceStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeTipSellerInvoices() : ?Collection
    {
        return $this->collection($this->tipSellerInvoices, new TipInvoiceSellerListTransformer);
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
