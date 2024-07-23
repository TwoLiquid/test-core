<?php

namespace App\Transformers\Api\General\Dashboard\Vybe\Request;

use App\Models\MongoDb\Vybe\Request\Unpublish\VybeUnpublishRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeUnpublishRequestTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Vybe\Request
 */
class VybeUnpublishRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'request_status'
    ];

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return array
     */
    public function transform(VybeUnpublishRequest $vybeUnpublishRequest) : array
    {
        return [
            'id' => $vybeUnpublishRequest->_id
        ];
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(VybeUnpublishRequest $vybeUnpublishRequest) : ?Item
    {
        $requestStatus = $vybeUnpublishRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_unpublish_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_unpublish_requests';
    }
}
