<?php

namespace App\Transformers\Api\Admin\Request\Vybe\UnsuspendRequest;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class VybeUnsuspendRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Vybe\UnsuspendRequest
 */
class VybeUnsuspendRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybeUnsuspendRequests;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'requests'
    ];

    /**
     * VybeUnsuspendRequestListPageTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $vybeUnsuspendRequests
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $vybeUnsuspendRequests
    )
    {
        /** @var EloquentCollection statuses */
        $this->statuses = $statuses;

        /** @var EloquentCollection vybeUnsuspendRequests */
        $this->vybeUnsuspendRequests = $vybeUnsuspendRequests;
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
        return $this->item([], new VybeUnsuspendRequestListPageFilterTransformer($this->statuses));
    }

    /**
     * @return Collection|null
     */
    public function includeRequests() : ?Collection
    {
        return $this->collection($this->vybeUnsuspendRequests, new VybeUnsuspendRequestTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_unsuspend_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_unsuspend_request_lists';
    }
}
