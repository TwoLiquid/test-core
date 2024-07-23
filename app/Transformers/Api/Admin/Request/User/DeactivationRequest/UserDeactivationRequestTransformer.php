<?php

namespace App\Transformers\Api\Admin\Request\User\DeactivationRequest;

use App\Models\MongoDb\User\Request\Deactivation\UserDeactivationRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class UserDeactivationRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\User\DeactivationRequest
 */
class UserDeactivationRequestTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'language',
        'request_status',
        'admin'
    ];

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return array
     */
    public function transform(UserDeactivationRequest $userDeactivationRequest) : array
    {
        return [
            'id'         => $userDeactivationRequest->_id,
            'waiting'    => Carbon::now()->diff($userDeactivationRequest->created_at)->format('%H:%I:%S'),
            'created_at' => $userDeactivationRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return Item|null
     */
    public function includeUser(UserDeactivationRequest $userDeactivationRequest) : ?Item
    {
        $user = null;

        if ($userDeactivationRequest->relationLoaded('user')) {
            $user = $userDeactivationRequest->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return Item|null
     */
    public function includeAdmin(UserDeactivationRequest $userDeactivationRequest) : ?Item
    {
        $admin = null;

        if ($userDeactivationRequest->relationLoaded('admin')) {
            $admin = $userDeactivationRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return Item|null
     */
    public function includeLanguage(UserDeactivationRequest $userDeactivationRequest) : ?Item
    {
        $language = $userDeactivationRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserDeactivationRequest $userDeactivationRequest) : ?Item
    {
        $requestStatus = $userDeactivationRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_deactivation_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_deactivation_requests';
    }
}
