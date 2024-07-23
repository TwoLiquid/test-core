<?php

namespace App\Transformers\Api\Admin\Request\Finance\WithdrawalRequest;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class WithdrawalRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Finance\WithdrawalRequest
 */
class WithdrawalRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $withdrawalRequests;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'requests'
    ];

    /**
     * WithdrawalRequestListPageTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $withdrawalRequests
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $withdrawalRequests
    )
    {
        /** @var EloquentCollection statuses */
        $this->statuses = $statuses;

        /** @var EloquentCollection withdrawalRequests */
        $this->withdrawalRequests = $withdrawalRequests;
    }

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item
     */
    public function includeForm() : Item
    {
        return $this->item([], new FormTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeFilters() : ?Item
    {
        return $this->item([], new WithdrawalRequestListPageFilterTransformer(
            $this->statuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeRequests() : ?Collection
    {
        return $this->collection($this->withdrawalRequests, new WithdrawalRequestTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'withdrawal_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'withdrawal_request_lists';
    }
}
