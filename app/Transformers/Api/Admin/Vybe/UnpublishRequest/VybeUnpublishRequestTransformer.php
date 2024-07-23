<?php

namespace App\Transformers\Api\Admin\Vybe\UnpublishRequest;

use App\Models\MongoDb\Vybe\Request\Unpublish\VybeUnpublishRequest;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeUnpublishRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\UnpublishRequest
 */
class VybeUnpublishRequestTransformer extends BaseTransformer
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
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return array
     */
    public function transform(VybeUnpublishRequest $vybeUnpublishRequest) : array
    {
        return [
            'id'                 => $vybeUnpublishRequest->_id,
            'message'            => $vybeUnpublishRequest->message,
            'toast_message_text' => $vybeUnpublishRequest->toast_message_text,
            'created_at'         => $vybeUnpublishRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return Item|null
     */
    public function includeVybe(VybeUnpublishRequest $vybeUnpublishRequest) : ?Item
    {
        $vybe = null;

        if ($vybeUnpublishRequest->relationLoaded('vybe')) {
            $vybe = $vybeUnpublishRequest->vybe;
        }

        return $vybe ? $this->item($vybe, new VybeTransformer) : null;
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return Item|null
     */
    public function includeStatus(VybeUnpublishRequest $vybeUnpublishRequest) : ?Item
    {
        $vybeStatusListItem = $vybeUnpublishRequest->getStatus();

        return $vybeStatusListItem ? $this->item($vybeStatusListItem, new VybeStatusTransformer) : null;
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return Item|null
     */
    public function includePreviousStatus(VybeUnpublishRequest $vybeUnpublishRequest) : ?Item
    {
        $previousVybeStatusListItem = $vybeUnpublishRequest->getPreviousStatus();

        return $previousVybeStatusListItem ? $this->item($previousVybeStatusListItem, new VybeStatusTransformer) : null;
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return Item|null
     */
    public function includeStatusStatus(VybeUnpublishRequest $vybeUnpublishRequest) : ?Item
    {
        $statusStatus = $vybeUnpublishRequest->getStatusStatus();

        return $statusStatus ? $this->item($statusStatus, new RequestFieldStatusTransformer) : null;
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
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return Item|null
     */
    public function includeToastMessageType(VybeUnpublishRequest $vybeUnpublishRequest) : ?Item
    {
        $toastMessageType = $vybeUnpublishRequest->getToastMessageType();

        return $toastMessageType ? $this->item($toastMessageType, new ToastMessageTypeTransformer) : null;
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
