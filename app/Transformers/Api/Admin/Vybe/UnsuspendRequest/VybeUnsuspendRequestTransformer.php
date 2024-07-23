<?php

namespace App\Transformers\Api\Admin\Vybe\UnsuspendRequest;

use App\Models\MongoDb\Vybe\Request\Unsuspend\VybeUnsuspendRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeUnsuspendRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\UnsuspendRequest
 */
class VybeUnsuspendRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe',
        'status',
        'previous_status',
        'status',
        'status_status',
        'toast_message_type',
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
            'id'                 => $vybeUnsuspendRequest->_id,
            'message'            => $vybeUnsuspendRequest->message,
            'toast_message_text' => $vybeUnsuspendRequest->toast_message_text,
            'created_at'         => $vybeUnsuspendRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeVybe(VybeUnsuspendRequest $vybeUnsuspendRequest) : ?Item
    {
        $vybe = null;

        if ($vybeUnsuspendRequest->relationLoaded('vybe')) {
            $vybe = $vybeUnsuspendRequest->vybe;
        }

        return $vybe ? $this->item($vybe, new VybeTransformer) : null;
    }

    /**
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeStatus(VybeUnsuspendRequest $vybeUnsuspendRequest) : ?Item
    {
        $vybeStatusListItem = $vybeUnsuspendRequest->getStatus();

        return $vybeStatusListItem ? $this->item($vybeStatusListItem, new VybeStatusTransformer) : null;
    }

    /**
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return Item|null
     */
    public function includePreviousStatus(VybeUnsuspendRequest $vybeUnsuspendRequest) : ?Item
    {
        $previousVybeStatusListItem = $vybeUnsuspendRequest->getPreviousStatus();

        return $previousVybeStatusListItem ? $this->item($previousVybeStatusListItem, new VybeStatusTransformer) : null;
    }

    /**
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeStatusStatus(VybeUnsuspendRequest $vybeUnsuspendRequest) : ?Item
    {
        $statusStatus = $vybeUnsuspendRequest->getStatusStatus();

        return $statusStatus ? $this->item($statusStatus, new RequestFieldStatusTransformer) : null;
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
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeToastMessageType(VybeUnsuspendRequest $vybeUnsuspendRequest) : ?Item
    {
        $toastMessageType = $vybeUnsuspendRequest->getToastMessageType();

        return $toastMessageType ? $this->item($toastMessageType, new ToastMessageTypeTransformer) : null;
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
