<?php

namespace App\Transformers\Api\General\Navbar\Request;

use App\Models\MongoDb\User\Request\Unsuspend\UserUnsuspendRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserUnsuspendRequestTransformer
 *
 * @package App\Transformers\Api\General\Navbar\Request
 */
class UserUnsuspendRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'request_status'
    ];

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return array
     */
    public function transform(UserUnsuspendRequest $userUnsuspendRequest) : array
    {
        return [
            'id' => $userUnsuspendRequest->_id
        ];
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserUnsuspendRequest $userUnsuspendRequest) : ?Item
    {
        $requestStatus = $userUnsuspendRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_unsuspend_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_unsuspend_requests';
    }
}
