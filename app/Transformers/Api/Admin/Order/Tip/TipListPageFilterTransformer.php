<?php

namespace App\Transformers\Api\Admin\Order\Tip;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class TipListPageFilterTransformer
 *
 * @package App\Transformers\Api\Admin\Order\Tip
 */
class TipListPageFilterTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybeTypes;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $invoiceBuyerStatuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $invoiceSellerStatuses;

    /**
     * TipListPageFilterTransformer constructor
     *
     * @param EloquentCollection $vybeTypes
     * @param EloquentCollection $invoiceBuyerStatuses
     * @param EloquentCollection $invoiceSellerStatuses
     */
    public function __construct(
        EloquentCollection $vybeTypes,
        EloquentCollection $invoiceBuyerStatuses,
        EloquentCollection $invoiceSellerStatuses
    )
    {
        $this->vybeTypes = $vybeTypes;
        $this->invoiceBuyerStatuses = $invoiceBuyerStatuses;
        $this->invoiceSellerStatuses = $invoiceSellerStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe_types',
        'invoice_buyer_statuses',
        'invoice_seller_statuses'
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
        $vybeTypes = $this->vybeTypes;

        return $this->collection($vybeTypes, new VybeTypeTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeInvoiceBuyerStatuses() : ?Collection
    {
        $invoiceBuyerStatuses = $this->invoiceBuyerStatuses;

        return $this->collection($invoiceBuyerStatuses, new InvoiceStatusTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeInvoiceSellerStatuses() : ?Collection
    {
        $invoiceSellerStatuses = $this->invoiceSellerStatuses;

        return $this->collection($invoiceSellerStatuses, new InvoiceStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'filters';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'filters';
    }
}
