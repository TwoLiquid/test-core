<?php

namespace App\Transformers\Api\Admin\Request\Vybe\DeletionRequest;

use App\Models\MongoDb\Vybe\Request\Deletion\VybeDeletionRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class VybeDeletionRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Vybe\DeletionRequest
 */
class VybeDeletionRequestTransformer extends BaseTransformer
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
     * @param VybeDeletionRequest $vybeDeletionRequest
     *
     * @return array
     */
    public function transform(VybeDeletionRequest $vybeDeletionRequest) : array
    {
        return [
            'id'         => $vybeDeletionRequest->_id,
            'waiting'    => Carbon::now()->diff($vybeDeletionRequest->created_at)->format('%H:%I:%S'),
            'sales'      => $vybeDeletionRequest->relationLoaded('vybe') ?
                $vybeDeletionRequest->vybe->order_items_count :
                null,
            'vybe_version' => $vybeDeletionRequest->relationLoaded('vybe') ?
                $vybeDeletionRequest->vybe->version :
                null,
            'created_at' => $vybeDeletionRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param VybeDeletionRequest $vybeDeletionRequest
     *
     * @return Item|null
     */
    public function includeUser(VybeDeletionRequest $vybeDeletionRequest) : ?Item
    {
        $user = null;

        if ($vybeDeletionRequest->relationLoaded('vybe')) {
            $vybe = $vybeDeletionRequest->vybe;

            if ($vybe->relationLoaded('user')) {
                $user = $vybe->user;
            }
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param VybeDeletionRequest $vybeDeletionRequest
     *
     * @return Item|null
     */
    public function includeAdmin(VybeDeletionRequest $vybeDeletionRequest) : ?Item
    {
        $admin = null;

        if ($vybeDeletionRequest->relationLoaded('admin')) {
            $admin = $vybeDeletionRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param VybeDeletionRequest $vybeDeletionRequest
     *
     * @return Item|null
     */
    public function includeLanguage(VybeDeletionRequest $vybeDeletionRequest) : ?Item
    {
        $language = $vybeDeletionRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
    }

    /**
     * @param VybeDeletionRequest $vybeDeletionRequest
     *
     * @return Item|null
     */
    public function includeVybeStatus(VybeDeletionRequest $vybeDeletionRequest) : ?Item
    {
        $vybeStatus = null;

        if ($vybeDeletionRequest->relationLoaded('vybe')) {
            $vybeStatus = $vybeDeletionRequest->vybe->getStatus();
        }

        return $vybeStatus ? $this->item($vybeStatus, new VybeStatusTransformer) : null;
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
