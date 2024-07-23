<?php

namespace App\Transformers\Api\Admin\Invoice\Seller;

use App\Transformers\Api\Admin\Invoice\Seller\OrderItem\OrderItemStatusTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class InvoiceSellerListPageFilterTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Seller
 */
class InvoiceSellerListPageFilterTransformer extends BaseTransformer
{
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
     * InvoiceSellerListPageFilterTransformer constructor
     *
     * @param EloquentCollection $vybeTypes
     * @param EloquentCollection $orderItemStatuses
     * @param EloquentCollection $invoiceStatuses
     */
    public function __construct(
        EloquentCollection $vybeTypes,
        EloquentCollection $orderItemStatuses,
        EloquentCollection $invoiceStatuses
    )
    {
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
        'invoice_statuses'
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
    public function includeOrderItemStatuses() : ?Collection
    {
        $orderItemStatuses = $this->orderItemStatuses;

        return $this->collection($orderItemStatuses, new OrderItemStatusTransformer);
    }

    public function includeInvoiceStatuses() : ?Collection
    {
        $invoiceStatuses = $this->invoiceStatuses;

        return $this->collection($invoiceStatuses, new InvoiceStatusTransformer);
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
