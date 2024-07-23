<?php

namespace App\Transformers\Api\Admin\Request\User\UnsuspendRequest;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserUnsuspendRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Request\User\UnsuspendRequest
 */
class UserUnsuspendRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $userUnsuspendRequests;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'requests'
    ];

    /**
     * UserUnsuspendRequestListPageTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $userUnsuspendRequests
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $userUnsuspendRequests
    )
    {
        /** @var EloquentCollection statuses */
        $this->statuses = $statuses;

        /** @var EloquentCollection userUnsuspendRequests */
        $this->userUnsuspendRequests = $userUnsuspendRequests;
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
        return $this->item([], new UserUnsuspendRequestListPageFilterTransformer($this->statuses));
    }

    /**
     * @return Collection|null
     */
    public function includeRequests() : ?Collection
    {
        return $this->collection($this->userUnsuspendRequests, new UserUnsuspendRequestTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_unsuspend_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_unsuspend_request_lists';
    }
}
