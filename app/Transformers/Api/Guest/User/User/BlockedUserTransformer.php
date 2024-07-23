<?php

namespace App\Transformers\Api\Guest\User\User;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;

/**
 * Class BlockedUserTransformer
 *
 * @package App\Transformers\Api\Guest\User\User
 */
class BlockedUserTransformer extends BaseTransformer
{
    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'       => $user->id,
            'auth_id'  => $user->auth_id,
            'username' => $user->username
        ];
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