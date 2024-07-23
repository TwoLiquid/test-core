<?php

namespace App\Transformers\Api\Guest\User\User;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;

/**
 * Class UserShortTransformer
 *
 * @package App\Transformers\Api\Guest\User\User
 */
class UserShortTransformer extends BaseTransformer
{
    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'              => $user->id,
            'auth_id'         => $user->auth_id,
            'username'        => $user->username,
            'avatar_id'       => $user->avatar_id,
            'voice_sample_id' => $user->voice_sample_id
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