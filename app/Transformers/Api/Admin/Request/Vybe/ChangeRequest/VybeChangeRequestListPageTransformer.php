<?php

namespace App\Transformers\Api\Admin\Request\Vybe\ChangeRequest;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class VybeChangeRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Vybe\ChangeRequest
 */
class VybeChangeRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybeChangeRequests;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'requests'
    ];

    /**
     * VybeChangeRequestListPageTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $vybeChangeRequests
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $vybeChangeRequests
    )
    {
        /** @var EloquentCollection statuses */
        $this->statuses = $statuses;

        /** @var EloquentCollection vybeChangeRequests */
        $this->vybeChangeRequests = $vybeChangeRequests;
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
        return $this->item([], new VybeChangeRequestListPageFilterTransformer($this->statuses));
    }

    /**
     * @return Collection|null
     */
    public function includeRequests() : ?Collection
    {
        return $this->collection($this->vybeChangeRequests, new VybeChangeRequestTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_change_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_change_request_lists';
    }
}
