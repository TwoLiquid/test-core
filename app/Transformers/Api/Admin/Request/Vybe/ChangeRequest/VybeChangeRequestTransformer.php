<?php

namespace App\Transformers\Api\Admin\Request\Vybe\ChangeRequest;

use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class VybeChangeRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Vybe\ChangeRequest
 */
class VybeChangeRequestTransformer extends BaseTransformer
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
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return array
     */
    public function transform(VybeChangeRequest $vybeChangeRequest) : array
    {
        return [
            'id'         => $vybeChangeRequest->_id,
            'sales'      => $vybeChangeRequest->relationLoaded('vybe') ?
                $vybeChangeRequest->vybe->order_items_count :
                null,
            'vybe_version' => $vybeChangeRequest->relationLoaded('vybe') ?
                $vybeChangeRequest->vybe->version :
                null,
            'waiting'    => Carbon::now()->diff($vybeChangeRequest->created_at)->format('%H:%I:%S'),
            'created_at' => $vybeChangeRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeUser(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $user = null;

        if ($vybeChangeRequest->relationLoaded('vybe')) {
            $vybe = $vybeChangeRequest->vybe;

            if ($vybe->relationLoaded('user')) {
                $user = $vybe->user;
            }
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeAdmin(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $admin = null;

        if ($vybeChangeRequest->relationLoaded('admin')) {
            $admin = $vybeChangeRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeLanguage(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $language = $vybeChangeRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Item|null
     */
    public function includeVybeStatus(VybeChangeRequest $vybeChangeRequest) : ?Item
    {
        $vybeStatus = null;

        if ($vybeChangeRequest->relationLoaded('vybe')) {
            $vybeStatus = $vybeChangeRequest->vybe->getStatus();
        }

        return $vybeStatus ? $this->item($vybeStatus, new VybeStatusTransformer) : null;
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
