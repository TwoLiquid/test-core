<?php

namespace App\Transformers\Api\Admin\Request\User\UnsuspendRequest;

use App\Models\MongoDb\User\Request\Unsuspend\UserUnsuspendRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class UserUnsuspendRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\User\UnsuspendRequest
 */
class UserUnsuspendRequestTransformer extends BaseTransformer
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
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return array
     */
    public function transform(UserUnsuspendRequest $userUnsuspendRequest) : array
    {
        return [
            'id'         => $userUnsuspendRequest->_id,
            'waiting'    => Carbon::now()->diff($userUnsuspendRequest->created_at)->format('%H:%I:%S'),
            'created_at' => $userUnsuspendRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeUser(UserUnsuspendRequest $userUnsuspendRequest) : ?Item
    {
        $user = null;

        if ($userUnsuspendRequest->relationLoaded('user')) {
            $user = $userUnsuspendRequest->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeAdmin(UserUnsuspendRequest $userUnsuspendRequest) : ?Item
    {
        $admin = null;

        if ($userUnsuspendRequest->relationLoaded('admin')) {
            $admin = $userUnsuspendRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeLanguage(UserUnsuspendRequest $userUnsuspendRequest) : ?Item
    {
        $language = $userUnsuspendRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserUnsuspendRequest $userUnsuspendRequest) : ?Item
    {
        $requestStatus = $userUnsuspendRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_unsuspend_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_unsuspend_requests';
    }
}
