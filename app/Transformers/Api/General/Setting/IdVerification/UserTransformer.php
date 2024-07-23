<?php

namespace App\Transformers\Api\General\Setting\IdVerification;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\General\Setting\IdVerification
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
            'id'                     => $user->id,
            'auth_id'                => $user->auth_id,
            'verification_suspended' => $user->verification_suspended
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
