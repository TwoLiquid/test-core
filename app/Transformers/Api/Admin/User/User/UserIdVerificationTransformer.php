<?php

namespace App\Transformers\Api\Admin\User\User;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserIdVerificationTransformer
 *
 * @package App\Transformers\Api\Admin\User\User
 */
class UserIdVerificationTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user_id_verification_status'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'                     => $user->id,
            'auth_id'                => $user->auth_id,
            'verification_suspended' => $user->verification_suspended
        ];
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeUserIdVerificationStatus(User $user) : ?Item
    {
        $userIdVerificationStatus = $user->getIdVerificationStatus();

        return $userIdVerificationStatus ? $this->item($userIdVerificationStatus, new UserIdVerificationStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'users';
    }
}
