<?php

namespace App\Transformers\Api\Admin\Request\User\IdVerificationRequest;

use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Item;

/**
 * Class UserIdVerificationRequestTransformer
 *
 * @package App\Transformers\Api\Admin\Request\User\IdVerificationRequest
 */
class UserIdVerificationRequestTransformer extends BaseTransformer
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
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return array
     */
    public function transform(UserIdVerificationRequest $userIdVerificationRequest) : array
    {
        return [
            'id'         => $userIdVerificationRequest->_id,
            'waiting'    => Carbon::now()->diff($userIdVerificationRequest->created_at)->format('%H:%I:%S'),
            'created_at' => $userIdVerificationRequest->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeUser(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $user = null;

        if ($userIdVerificationRequest->relationLoaded('user')) {
            $user = $userIdVerificationRequest->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeAdmin(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $admin = null;

        if ($userIdVerificationRequest->relationLoaded('admin')) {
            $admin = $userIdVerificationRequest->admin;
        }

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeLanguage(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $language = $userIdVerificationRequest->getLanguage();

        return $language ? $this->item($language, new LanguageTransformer) : null;
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserIdVerificationRequest $userIdVerificationRequest) : ?Item
    {
        $requestStatus = $userIdVerificationRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_id_verification_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_id_verification_requests';
    }
}
