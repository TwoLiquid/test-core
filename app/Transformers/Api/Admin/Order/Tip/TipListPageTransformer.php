<?php

namespace App\Transformers\Api\Admin\Order\Tip;

use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class TipListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Order\Tip
 */
class TipListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $tips;

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
     * TipListPageTransformer constructor
     *
     * @param EloquentCollection $tips
     * @param EloquentCollection $vybeTypes
     * @param EloquentCollection $invoiceBuyerStatuses
     * @param EloquentCollection $invoiceSellerStatuses
     */
    public function __construct(
        EloquentCollection $tips,
        EloquentCollection $vybeTypes,
        EloquentCollection $invoiceBuyerStatuses,
        EloquentCollection $invoiceSellerStatuses
    )
    {
        $this->tips = $tips;
        $this->vybeTypes = $vybeTypes;
        $this->invoiceBuyerStatuses = $invoiceBuyerStatuses;
        $this->invoiceSellerStatuses = $invoiceSellerStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe_types',
        'invoice_statuses',
        'filters',
        'tips'
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
        return $this->item([], new TipListPageFilterTransformer(
            $this->vybeTypes,
            $this->invoiceBuyerStatuses,
            $this->invoiceSellerStatuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeTips() : ?Collection
    {
        return $this->collection($this->tips, new TipListTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tip_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tip_lists';
    }
}
