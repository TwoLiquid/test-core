<?php

namespace App\Transformers\Api\General\Dashboard\Vybe\Request;

use App\Models\MongoDb\Vybe\Request\Unsuspend\VybeUnsuspendRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeUnsuspendRequestTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Vybe\Request
 */
class VybeUnsuspendRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'request_status'
    ];

    /**
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return array
     */
    public function transform(VybeUnsuspendRequest $vybeUnsuspendRequest) : array
    {
        return [
            'id' => $vybeUnsuspendRequest->_id
        ];
    }

    /**
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(VybeUnsuspendRequest $vybeUnsuspendRequest) : ?Item
    {
        $requestStatus = $vybeUnsuspendRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_unsuspend_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_unsuspend_requests';
    }
}
