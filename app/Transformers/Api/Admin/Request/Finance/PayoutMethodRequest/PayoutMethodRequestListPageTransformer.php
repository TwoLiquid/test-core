<?php

namespace App\Transformers\Api\Admin\Request\Finance\PayoutMethodRequest;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class PayoutMethodRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Finance\PayoutMethodRequest
 */
class PayoutMethodRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $payoutMethodRequests;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'requests'
    ];

    /**
     * PayoutMethodRequestListPageTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $payoutMethodRequests
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $payoutMethodRequests
    )
    {
        /** @var EloquentCollection statuses */
        $this->statuses = $statuses;

        /** @var EloquentCollection payoutMethodRequests */
        $this->payoutMethodRequests = $payoutMethodRequests;
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
        return $this->item([], new PayoutMethodRequestListPageFilterTransformer(
            $this->statuses
        ));
    }

    /**
     * @return Collection|null
     */
    public function includeRequests() : ?Collection
    {
        return $this->collection($this->payoutMethodRequests, new PayoutMethodRequestTransformer);
    }
    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payout_method_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payout_method_request_lists';
    }
}
