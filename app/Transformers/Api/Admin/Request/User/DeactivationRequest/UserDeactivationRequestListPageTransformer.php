<?php

namespace App\Transformers\Api\Admin\Request\User\DeactivationRequest;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserDeactivationRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Request\User\DeactivationRequest
 */
class UserDeactivationRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $userDeactivationRequests;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'requests'
    ];

    /**
     * UserDeactivationRequestListPageTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $userDeactivationRequests
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $userDeactivationRequests
    )
    {
        /** @var EloquentCollection statuses */
        $this->statuses = $statuses;

        /** @var EloquentCollection userDeactivationRequests */
        $this->userDeactivationRequests = $userDeactivationRequests;
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
        return $this->item([], new UserDeactivationRequestListPageFilterTransformer($this->statuses));
    }

    /**
     * @return Collection|null
     */
    public function includeRequests() : ?Collection
    {
        return $this->collection($this->userDeactivationRequests, new UserDeactivationRequestTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_deactivation_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_deactivation_request_lists';
    }
}
