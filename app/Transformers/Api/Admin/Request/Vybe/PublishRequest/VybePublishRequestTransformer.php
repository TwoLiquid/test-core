<?php

namespace App\Transformers\Api\Admin\Request\Vybe\PublishRequest;

use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class VybePublishRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Vybe\PublishRequest
 */
class VybePublishRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'language',
        'vybe_status',
        'request_status',
        'admin'
    ];

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return array
     */
    public function transform(VybePublishRequest $vybePublishRequest) : array
    {
        return [
            'id'         => $vybePublishRequest->_id,
            'waiting'    => Carbon::now()->diff($vybePublishRequest->created_at)->format('%H:%I:%S'),
            'sales'      => $vybePublishRequest->relationLoaded('vybe') ?
                $vybePublishRequest->vybe->order_items_count :
                null,
            'vybe_version' => $vybePublishRequest->relationLoaded('vybe') ?
                $vybePublishRequest->vybe->version :
                null,
            'created_at' => $vybePublishRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeUser(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $user = null;

        if ($vybePublishRequest->relationLoaded('vybe')) {
            $vybe = $vybePublishRequest->vybe;

            if ($vybe->relationLoaded('user')) {
                $user = $vybe->user;
            }
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeAdmin(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $admin = null;

        if ($vybePublishRequest->relationLoaded('admin')) {
            $admin = $vybePublishRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeLanguage(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $language = $vybePublishRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Item|null
     */
    public function includeVybeStatus(VybePublishRequest $vybePublishRequest) : ?Item
    {
        $vybeStatus = null;

        if ($vybePublishRequest->relationLoaded('vybe')) {
            $vybeStatus = $vybePublishRequest->vybe->getStatus();
        }

        return $vybeStatus ? $this->item($vybeStatus, new VybeStatusTransformer) : null;
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
