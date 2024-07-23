<?php

namespace App\Transformers\Api\Admin\Request\User\DeletionRequest;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserDeletionRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Request\User\DeletionRequest
 */
class UserDeletionRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $userDeletionRequests;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'requests'
    ];

    /**
     * UserDeletionRequestListPageTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $userDeletionRequests
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $userDeletionRequests
    )
    {
        /** @var EloquentCollection statuses */
        $this->statuses = $statuses;

        /** @var EloquentCollection statuses */
        $this->userDeletionRequests = $userDeletionRequests;
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
        return $this->item([], new UserDeletionRequestListPageFilterTransformer($this->statuses));
    }

    /**
     * @return Collection|null
     */
    public function includeRequests() : ?Collection
    {
        return $this->collection($this->userDeletionRequests, new UserDeletionRequestTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_deletion_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_deletion_request_lists';
    }
}
