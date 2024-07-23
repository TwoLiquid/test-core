<?php

namespace App\Transformers\Api\General\Dashboard\Vybe\Request;

use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeChangeRequestTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Vybe\Request
 */
class VybeChangeRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'request_status'
    ];

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return array
     */
    public function transform(VybeChangeRequest $vybeChangeRequest) : array
    {
        return [
            'id' => $vybeChangeRequest->_id
        ];
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $requestStatus = $vybeChangeRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_change_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_change_requests';
    }
}
