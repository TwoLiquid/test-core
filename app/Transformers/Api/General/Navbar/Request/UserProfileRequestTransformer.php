<?php

namespace App\Transformers\Api\General\Navbar\Request;

use App\Models\MongoDb\User\Request\Profile\UserProfileRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserProfileRequestTransformer
 *
 * @package App\Transformers\Api\General\Navbar\Request
 */
class UserProfileRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'request_status'
    ];

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     */
    public function transform(UserProfileRequest $userProfileRequest) : array
    {
        return [
            'id' => $userProfileRequest->_id
        ];
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserProfileRequest $userProfileRequest) : ?Item
    {
        $requestStatus = $userProfileRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_profile_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_profile_requests';
    }
}
