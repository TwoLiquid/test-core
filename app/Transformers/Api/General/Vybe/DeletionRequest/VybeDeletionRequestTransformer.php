<?php

namespace App\Transformers\Api\General\Vybe\DeletionRequest;

use App\Models\MongoDb\Vybe\Request\Deletion\VybeDeletionRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeDeletionRequestTransformer
 *
 * @package App\Transformers\Api\General\Vybe\DeletionRequest
 */
class VybeDeletionRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'status',
        'previous_status',
        'status',
        'status_status',
        'toast_message_type',
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
            'id'                 => $vybeDeletionRequest->_id,
            'message'            => $vybeDeletionRequest->message,
            'toast_message_text' => $vybeDeletionRequest->toast_message_text,
            'created_at'         => $vybeDeletionRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param VybeDeletionRequest $vybeDeletionRequest
     *
     * @return Item|null
     */
    public function includeStatus(VybeDeletionRequest $vybeDeletionRequest) : ?Item
    {
        $vybeStatusListItem = $vybeDeletionRequest->getStatus();

        return $vybeStatusListItem ? $this->item($vybeStatusListItem, new VybeStatusTransformer) : null;
    }

    /**
     * @param VybeDeletionRequest $vybeDeletionRequest
     *
     * @return Item|null
     */
    public function includePreviousStatus(VybeDeletionRequest $vybeDeletionRequest) : ?Item
    {
        $previousVybeStatusListItem = $vybeDeletionRequest->getPreviousStatus();

        return $previousVybeStatusListItem ? $this->item($previousVybeStatusListItem, new VybeStatusTransformer) : null;
    }

    /**
     * @param VybeDeletionRequest $vybeDeletionRequest
     *
     * @return Item|null
     */
    public function includeStatusStatus(VybeDeletionRequest $vybeDeletionRequest) : ?Item
    {
        $statusStatus = $vybeDeletionRequest->getStatusStatus();

        return $statusStatus ? $this->item($statusStatus, new RequestFieldStatusTransformer) : null;
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
     * @param VybeDeletionRequest $vybeDeletionRequest
     *
     * @return Item|null
     */
    public function includeToastMessageType(VybeDeletionRequest $vybeDeletionRequest) : ?Item
    {
        $toastMessageType = $vybeDeletionRequest->getToastMessageType();

        return $toastMessageType ? $this->item($toastMessageType, new ToastMessageTypeTransformer) : null;
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
