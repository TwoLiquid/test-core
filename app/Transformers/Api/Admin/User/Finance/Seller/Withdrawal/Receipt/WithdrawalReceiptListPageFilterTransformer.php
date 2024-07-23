<?php

namespace App\Transformers\Api\Admin\User\Finance\Seller\Withdrawal\Receipt;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class WithdrawalReceiptListPageFilterTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Seller\Withdrawal\Receipt
 */
class WithdrawalReceiptListPageFilterTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $withdrawalReceiptStatuses;

    /**
     * WithdrawalReceiptListPageFilterTransformer constructor
     *
     * @param EloquentCollection $withdrawalReceiptStatuses
     */
    public function __construct(
        EloquentCollection $withdrawalReceiptStatuses
    )
    {
        $this->withdrawalReceiptStatuses = $withdrawalReceiptStatuses;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'statuses'
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
        $withdrawalReceiptStatuses = $this->withdrawalReceiptStatuses;

        return $this->collection($withdrawalReceiptStatuses, new WithdrawalReceiptStatusTransformer);
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
