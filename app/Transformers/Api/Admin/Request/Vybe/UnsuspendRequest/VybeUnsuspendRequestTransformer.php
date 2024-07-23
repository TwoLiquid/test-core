<?php

namespace App\Transformers\Api\Admin\Request\Vybe\UnsuspendRequest;

use App\Models\MongoDb\Vybe\Request\Unsuspend\VybeUnsuspendRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class VybeUnsuspendRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Vybe\UnsuspendRequest
 */
class VybeUnsuspendRequestTransformer extends BaseTransformer
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
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return array
     */
    public function transform(VybeUnsuspendRequest $vybeUnsuspendRequest) : array
    {
        return [
            'id'         => $vybeUnsuspendRequest->_id,
            'waiting'    => Carbon::now()->diff($vybeUnsuspendRequest->created_at)->format('%H:%I:%S'),
            'sales'      => $vybeUnsuspendRequest->relationLoaded('vybe') ?
                $vybeUnsuspendRequest->vybe->order_items_count :
                null,
            'vybe_version' => $vybeUnsuspendRequest->relationLoaded('vybe') ?
                $vybeUnsuspendRequest->vybe->version :
                null,
            'created_at' => $vybeUnsuspendRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeUser(VybeUnsuspendRequest $vybeUnsuspendRequest) : ?Item
    {
        $user = null;

        if ($vybeUnsuspendRequest->relationLoaded('vybe')) {
            $vybe = $vybeUnsuspendRequest->vybe;

            if ($vybe->relationLoaded('user')) {
                $user = $vybe->user;
            }
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeAdmin(VybeUnsuspendRequest $vybeUnsuspendRequest) : ?Item
    {
        $admin = null;

        if ($vybeUnsuspendRequest->relationLoaded('admin')) {
            $admin = $vybeUnsuspendRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeLanguage(VybeUnsuspendRequest $vybeUnsuspendRequest) : ?Item
    {
        $language = $vybeUnsuspendRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
    }

    /**
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeVybeStatus(VybeUnsuspendRequest $vybeUnsuspendRequest) : ?Item
    {
        $vybeStatus = null;

        if ($vybeUnsuspendRequest->relationLoaded('vybe')) {
            $vybeStatus = $vybeUnsuspendRequest->vybe->getStatus();
        }

        return $vybeStatus ? $this->item($vybeStatus, new VybeStatusTransformer) : null;
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
