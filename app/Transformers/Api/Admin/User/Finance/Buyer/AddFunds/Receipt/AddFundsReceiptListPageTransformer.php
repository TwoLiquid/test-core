<?php

namespace App\Transformers\Api\Admin\User\Finance\Buyer\AddFunds\Receipt;

use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusList;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class AddFundsReceiptListPageTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Buyer\AddFunds\Receipt
 */
class AddFundsReceiptListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $addFundsReceipts;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * AddFundsReceiptListPageTransformer constructor
     *
     * @param EloquentCollection $addFundsReceipts
     * @param EloquentCollection $statuses
     */
    public function __construct(
        EloquentCollection $addFundsReceipts,
        EloquentCollection $statuses
    )
    {
        $this->addFundsReceipts = $addFundsReceipts;
        $this->statuses = $statuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'statuses',
        'filters',
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
     * @return Collection|null
     */
    public function includeStatuses() : ?Collection
    {
        $statuses = AddFundsReceiptStatusList::getItems();

        return $this->collection($statuses, new AddFundsReceiptStatusTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeFilters() : ?Item
    {
        return $this->item([], new AddFundsReceiptListPageFilterTransformer(
            $this->statuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeAddFundsReceipts() : ?Collection
    {
        return $this->collection($this->addFundsReceipts, new AddFundsReceiptListTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'add_funds_receipt_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'add_funds_receipt_lists';
    }
}
