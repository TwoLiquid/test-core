<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Tip;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class TipInvoiceForSellerPageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Seller\Invoice\Tip
 */
class TipInvoiceForSellerPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $tipInvoices;

    /**
     * TipInvoiceForSellerPageTransformer constructor
     */
    public function __construct(
        EloquentCollection $tipInvoices
    )
    {
        /** @var EloquentCollection tipInvoices */
        $this->tipInvoices = $tipInvoices;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'tip_invoices'
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
    public function includeTipInvoices() : Collection
    {
        return $this->collection($this->tipInvoices, new TipInvoiceTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tip_invoice_for_seller_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tip_invoice_for_seller_pages';
    }
}
