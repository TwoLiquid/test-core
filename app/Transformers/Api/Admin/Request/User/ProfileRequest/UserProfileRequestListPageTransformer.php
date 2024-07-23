<?php

namespace App\Transformers\Api\Admin\Request\User\ProfileRequest;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserProfileRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Request\User\ProfileRequest
 */
class UserProfileRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $userProfileRequests;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'requests'
    ];

    /**
     * UserProfileRequestListPageTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $userProfileRequests
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $userProfileRequests
    )
    {
        /** @var EloquentCollection statuses */
        $this->statuses = $statuses;

        /** @var EloquentCollection userProfileRequests */
        $this->userProfileRequests = $userProfileRequests;
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
        return $this->item([], new UserProfileRequestListPageFilterTransformer($this->statuses));
    }

    /**
     * @return Collection|null
     */
    public function includeRequests() : ?Collection
    {
        return $this->collection($this->userProfileRequests, new UserProfileRequestTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_profile_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_profile_request_lists';
    }
}
