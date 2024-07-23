<?php

namespace App\Transformers\Api\General\Navbar\Request;

use App\Models\MongoDb\User\Request\Deactivation\UserDeactivationRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class UserDeactivationRequestTransformer
 *
 * @package App\Transformers\Api\General\Navbar\Request
 */
class UserDeactivationRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'request_status'
    ];

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return array
     */
    public function transform(UserDeactivationRequest $userDeactivationRequest) : array
    {
        return [
            'id' => $userDeactivationRequest->_id
        ];
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserDeactivationRequest $userDeactivationRequest) : ?Item
    {
        $requestStatus = $userDeactivationRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_deactivation_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_deactivation_requests';
    }
}
