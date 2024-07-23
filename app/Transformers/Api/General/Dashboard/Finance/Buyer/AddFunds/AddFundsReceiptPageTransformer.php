<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Buyer\AddFunds;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class AddFundsReceiptPageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Buyer\AddFunds
 */
class AddFundsReceiptPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $addFundsReceipts;

    /**
     * AddFundsReceiptPageTransformer constructor
     */
    public function __construct(
        EloquentCollection $addFundsReceipts
    )
    {
        /** @var EloquentCollection addFundsReceipts */
        $this->addFundsReceipts = $addFundsReceipts;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'add_funds_receipts'
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
    public function includeAddFundsReceipts() : Collection
    {
        return $this->collection($this->addFundsReceipts, new AddFundsReceiptTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'add_funds_receipt_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'add_funds_receipt_pages';
    }
}
