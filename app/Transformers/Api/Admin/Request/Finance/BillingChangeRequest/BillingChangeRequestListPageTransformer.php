<?php

namespace App\Transformers\Api\Admin\Request\Finance\BillingChangeRequest;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class BillingChangeRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Finance\BillingChangeRequest
 */
class BillingChangeRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $billingChangeRequests;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'requests'
    ];

    /**
     * BillingChangeRequestListPageTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $billingChangeRequests
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $billingChangeRequests
    )
    {
        /** @var EloquentCollection statuses */
        $this->statuses = $statuses;

        /** @var EloquentCollection billingChangeRequests */
        $this->billingChangeRequests = $billingChangeRequests;
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
        return $this->item([], new BillingChangeRequestListPageFilterTransformer($this->statuses));
    }

    /**
     * @return Collection|null
     */
    public function includeRequests() : ?Collection
    {
        return $this->collection($this->billingChangeRequests, new BillingChangeRequestTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'billing_change_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'billing_change_request_lists';
    }
}
