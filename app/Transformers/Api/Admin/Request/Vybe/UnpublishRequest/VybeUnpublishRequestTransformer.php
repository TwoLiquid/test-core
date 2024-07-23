<?php

namespace App\Transformers\Api\Admin\Request\Vybe\UnpublishRequest;

use App\Models\MongoDb\Vybe\Request\Unpublish\VybeUnpublishRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class VybeUnpublishRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Vybe\UnpublishRequest
 */
class VybeUnpublishRequestTransformer extends BaseTransformer
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
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return array
     */
    public function transform(VybeUnpublishRequest $vybeUnpublishRequest) : array
    {
        return [
            'id'         => $vybeUnpublishRequest->_id,
            'waiting'    => Carbon::now()->diff($vybeUnpublishRequest->created_at)->format('%H:%I:%S'),
            'sales'      => $vybeUnpublishRequest->relationLoaded('vybe') ?
                $vybeUnpublishRequest->vybe->order_items_count :
                null,
            'vybe_version' => $vybeUnpublishRequest->relationLoaded('vybe') ?
                $vybeUnpublishRequest->vybe->version :
                null,
            'created_at' => $vybeUnpublishRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return Item|null
     */
    public function includeUser(VybeUnpublishRequest $vybeUnpublishRequest) : ?Item
    {
        $user = null;

        if ($vybeUnpublishRequest->relationLoaded('vybe')) {
            $vybe = $vybeUnpublishRequest->vybe;

            if ($vybe->relationLoaded('user')) {
                $user = $vybe->user;
            }
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return Item|null
     */
    public function includeAdmin(VybeUnpublishRequest $vybeUnpublishRequest) : ?Item
    {
        $admin = null;

        if ($vybeUnpublishRequest->relationLoaded('admin')) {
            $admin = $vybeUnpublishRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return Item|null
     */
    public function includeLanguage(VybeUnpublishRequest $vybeUnpublishRequest) : ?Item
    {
        $language = $vybeUnpublishRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return Item|null
     */
    public function includeVybeStatus(VybeUnpublishRequest $vybeUnpublishRequest) : ?Item
    {
        $vybeStatus = null;

        if ($vybeUnpublishRequest->relationLoaded('vybe')) {
            $vybeStatus = $vybeUnpublishRequest->vybe->getStatus();
        }

        return $vybeStatus ? $this->item($vybeStatus, new VybeStatusTransformer) : null;
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
