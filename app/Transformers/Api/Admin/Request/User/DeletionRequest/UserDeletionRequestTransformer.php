<?php

namespace App\Transformers\Api\Admin\Request\User\DeletionRequest;

use App\Models\MongoDb\User\Request\Deletion\UserDeletionRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class UserDeletionRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\User\DeletionRequest
 */
class UserDeletionRequestTransformer extends BaseTransformer
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
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return array
     */
    public function transform(UserDeletionRequest $userDeletionRequest) : array
    {
        return [
            'id'         => $userDeletionRequest->_id,
            'waiting'    => Carbon::now()->diff($userDeletionRequest->created_at)->format('%H:%I:%S'),
            'created_at' => $userDeletionRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return Item|null
     */
    public function includeUser(UserDeletionRequest $userDeletionRequest) : ?Item
    {
        $user = null;

        if ($userDeletionRequest->relationLoaded('user')) {
            $user = $userDeletionRequest->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return Item|null
     */
    public function includeAdmin(UserDeletionRequest $userDeletionRequest) : ?Item
    {
        $admin = null;

        if ($userDeletionRequest->relationLoaded('admin')) {
            $admin = $userDeletionRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return Item|null
     */
    public function includeLanguage(UserDeletionRequest $userDeletionRequest) : ?Item
    {
        $language = $userDeletionRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
    }

    /**
     * @param UserDeletionRequest $userDeletionRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserDeletionRequest $userDeletionRequest) : ?Item
    {
        $requestStatus = $userDeletionRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_deletion_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_deletion_requests';
    }
}
