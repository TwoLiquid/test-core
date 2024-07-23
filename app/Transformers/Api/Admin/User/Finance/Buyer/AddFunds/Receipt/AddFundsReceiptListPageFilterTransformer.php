<?php

namespace App\Transformers\Api\Admin\User\Finance\Buyer\AddFunds\Receipt;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class AddFundsReceiptListPageFilterTransformer
 *
 * @package App\Transformers\Api\Admin\User\Finance\Buyer\AddFunds\Receipt
 */
class AddFundsReceiptListPageFilterTransformer extends BaseTransformer
{

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * AddFundsReceiptListPageFilterTransformer constructor
     *
     * @param EloquentCollection $statuses
     */
    public function __construct(
        EloquentCollection $statuses
    )
    {
        $this->statuses = $statuses;
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

    public function includeStatuses() : ?Collection
    {
        $statuses = $this->statuses;

        return $this->collection($statuses, new AddFundsReceiptStatusTransformer);
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
