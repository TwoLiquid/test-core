<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class InvoiceForSellerPageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice
 */
class InvoiceForSellerPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $invoices;

    /**
     * InvoiceForSellerPageTransformer constructor
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
        return 'invoice_for_seller_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'invoice_for_seller_pages';
    }
}
