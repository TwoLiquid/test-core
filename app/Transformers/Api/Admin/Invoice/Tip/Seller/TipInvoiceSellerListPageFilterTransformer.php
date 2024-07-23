<?php

namespace App\Transformers\Api\Admin\Invoice\Tip\Seller;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class TipInvoiceSellerListPageFilterTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Tip\Seller
 */
class TipInvoiceSellerListPageFilterTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $orderItemStatuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $invoiceStatuses;

    /**
     * TipInvoiceSellerListPageFilterTransformer constructor
     *
     * @param EloquentCollection $orderItemStatuses
     * @param EloquentCollection $invoiceStatuses
     */
    public function __construct(
        EloquentCollection $orderItemStatuses,
        EloquentCollection $invoiceStatuses
    )
    {
        $this->orderItemStatuses = $orderItemStatuses;
        $this->invoiceStatuses = $invoiceStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
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
    public function includeOrderItemStatuses() : ?Collection
    {
        $orderItemStatuses = $this->orderItemStatuses;

        return $this->collection($orderItemStatuses, new OrderItemStatusTransformer);
    }

    /**
     * @return Collection|null
     */
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
