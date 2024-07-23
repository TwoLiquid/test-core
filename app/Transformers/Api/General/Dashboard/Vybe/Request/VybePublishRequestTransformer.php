<?php

namespace App\Transformers\Api\General\Dashboard\Vybe\Request;

use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybePublishRequestTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Vybe\Request
 */
class VybePublishRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'request_status'
    ];

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return array
     */
    public function transform(VybePublishRequest $vybePublishRequest) : array
    {
        return [
            'id' => $vybePublishRequest->_id
        ];
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $requestStatus = $vybePublishRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_publish_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_publish_requests';
    }
}
