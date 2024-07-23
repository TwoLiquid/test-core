<?php

namespace App\Transformers\Api\Admin\Request\User\ProfileRequest;

use App\Models\MongoDb\User\Request\Profile\UserProfileRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class UserProfileRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\User\ProfileRequest
 */
class UserProfileRequestTransformer extends BaseTransformer
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
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     */
    public function transform(UserProfileRequest $userProfileRequest) : array
    {
        return [
            'id'         => $userProfileRequest->_id,
            'waiting'    => Carbon::now()->diff($userProfileRequest->created_at)->format('%H:%I:%S'),
            'created_at' => $userProfileRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeUser(UserProfileRequest $userProfileRequest) : ?Item
    {
        $user = null;

        if ($userProfileRequest->relationLoaded('user')) {
            $user = $userProfileRequest->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeAdmin(UserProfileRequest $userProfileRequest) : ?Item
    {
        $admin = null;

        if ($userProfileRequest->relationLoaded('admin')) {
            $admin = $userProfileRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeLanguage(UserProfileRequest $userProfileRequest) : ?Item
    {
        $language = $userProfileRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserProfileRequest $userProfileRequest) : ?Item
    {
        $requestStatus = $userProfileRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_profile_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_profile_requests';
    }
}
