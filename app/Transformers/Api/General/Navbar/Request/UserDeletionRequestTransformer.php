<?php

namespace App\Transformers\Api\General\Navbar\Request;

use App\Models\MongoDb\User\Request\Deletion\UserDeletionRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserDeletionRequestTransformer
 *
 * @package App\Transformers\Api\General\Navbar\Request
 */
class UserDeletionRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'request_status'
    ];

    /**
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return array
     */
    public function transform(UserDeletionRequest $userDeletionRequest) : array
    {
        return [
            'id' => $userDeletionRequest->_id
        ];
    }

    /**
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserDeletionRequest $userDeletionRequest) : ?Item
    {
        $requestStatus = $userDeletionRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_deletion_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_deletion_requests';
    }
}
