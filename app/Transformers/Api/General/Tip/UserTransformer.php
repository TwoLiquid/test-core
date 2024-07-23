<?php

namespace App\Transformers\Api\General\Tip;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemUserTransformer
 *
 * @package App\Transformers\Api\General\Tipping
 */
class UserTransformer extends BaseTransformer
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
