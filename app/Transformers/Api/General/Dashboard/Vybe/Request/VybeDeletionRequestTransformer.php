<?php

namespace App\Transformers\Api\General\Dashboard\Vybe\Request;

use App\Models\MongoDb\Vybe\Request\Deletion\VybeDeletionRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeDeletionRequestTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Vybe\Request
 */
class VybeDeletionRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'request_status'
    ];

    /**
     * @param VybeDeletionRequest $vybeDeletionRequest
     *
     * @return array
     */
    public function transform(VybeDeletionRequest $vybeDeletionRequest) : array
    {
        return [
            'id' => $vybeDeletionRequest->_id
        ];
    }

    /**
     * @param VybeDeletionRequest $vybeDeletionRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(VybeDeletionRequest $vybeDeletionRequest) : ?Item
    {
        $requestStatus = $vybeDeletionRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_deletion_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_deletion_requests';
    }
}
