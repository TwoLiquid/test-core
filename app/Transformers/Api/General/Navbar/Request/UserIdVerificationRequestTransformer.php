<?php

namespace App\Transformers\Api\General\Navbar\Request;

use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserIdVerificationRequestTransformer
 *
 * @package App\Transformers\Api\General\Navbar\Request
 */
class UserIdVerificationRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'request_status'
    ];

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return array
     */
    public function transform(UserIdVerificationRequest $userIdVerificationRequest) : array
    {
        return [
            'id' => $userIdVerificationRequest->_id
        ];
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $requestStatus = $userIdVerificationRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_id_verification_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_id_verification_requests';
    }
}
