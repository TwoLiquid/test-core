<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Buyer\Invoice;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class InvoiceForBuyerPageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Buyer\Invoice
 */
class InvoiceForBuyerPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $invoices;

    /**
     * InvoiceForBuyerPageTransformer constructor
     */
    public function __construct(
        EloquentCollection $invoices
    )
    {
        /** @var EloquentCollection invoices */
        $this->invoices = $invoices;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'invoices'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item|null
     */
    public function includeForm() : ?Item
    {
        return $this->item([], new FormTransformer);
    }

    /**
     * @return Collection
     */
    public function includeInvoices() : Collection
    {
        return $this->collection($this->invoices, new OrderInvoiceTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'invoice_for_buyer_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'invoice_for_buyer_pages';
    }
}
