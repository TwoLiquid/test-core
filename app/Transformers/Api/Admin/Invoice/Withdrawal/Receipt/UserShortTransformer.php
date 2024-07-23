<?php

namespace App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;

/**
 * Class UserShortTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt
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
