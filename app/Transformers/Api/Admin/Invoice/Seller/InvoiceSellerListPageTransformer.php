<?php

namespace App\Transformers\Api\Admin\Invoice\Seller;

use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Order\Item\Status\OrderItemStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Transformers\Api\Admin\Invoice\Seller\OrderItem\OrderItemStatusTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class InvoiceSellerListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Seller
 */
class InvoiceSellerListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $sellerInvoices;

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
    protected EloquentCollection $invoiceStatuses;

    /**
     * InvoiceSellerListPageTransformer constructor
     *
     * @param EloquentCollection $sellerInvoices
     * @param EloquentCollection $vybeTypes
     * @param EloquentCollection $orderItemStatuses,
     * @param EloquentCollection $invoiceStatuses
     */
    public function __construct(
        EloquentCollection $sellerInvoices,
        EloquentCollection $vybeTypes,
        EloquentCollection $orderItemStatuses,
        EloquentCollection $invoiceStatuses
    )
    {
        $this->sellerInvoices = $sellerInvoices;
        $this->vybeTypes = $vybeTypes;
        $this->orderItemStatuses = $orderItemStatuses;
        $this->invoiceStatuses = $invoiceStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe_types',
        'order_item_statuses',
        'invoice_statuses',
        'filters',
        'seller_invoices'
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
        return $this->item([], new InvoiceSellerListPageFilterTransformer(
            $this->vybeTypes,
            $this->orderItemStatuses,
            $this->invoiceStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeSellerInvoices() : ?Collection
    {
        return $this->collection($this->sellerInvoices, new InvoiceSellerListTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'seller_invoice_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'seller_invoice_lists';
    }
}
