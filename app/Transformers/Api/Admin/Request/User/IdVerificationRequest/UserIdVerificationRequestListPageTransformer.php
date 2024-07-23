<?php

namespace App\Transformers\Api\Admin\Request\User\IdVerificationRequest;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserIdVerificationRequestListPageTransformer
 *
 * @package App\Transformers\Api\Admin\Request\User\IdVerificationRequest
 */
class UserIdVerificationRequestListPageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $statuses;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $userIdVerificationRequests;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'filters',
        'requests'
    ];

    /**
     * UserIdVerificationRequestListPageTransformer constructor
     *
     * @param EloquentCollection $statuses
     * @param EloquentCollection $userIdVerificationRequests
     */
    public function __construct(
        EloquentCollection $statuses,
        EloquentCollection $userIdVerificationRequests
    )
    {
        /** @var EloquentCollection statuses */
        $this->statuses = $statuses;

        /** @var EloquentCollection userIdVerificationRequests */
        $this->userIdVerificationRequests = $userIdVerificationRequests;
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
        return $this->item([], new UserIdVerificationRequestListPageFilterTransformer($this->statuses));
    }

    /**
     * @return Collection|null
     */
    public function includeRequests() : ?Collection
    {
        return $this->collection($this->userIdVerificationRequests, new UserIdVerificationRequestTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_id_verification_request_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_id_verification_request_lists';
    }
}
