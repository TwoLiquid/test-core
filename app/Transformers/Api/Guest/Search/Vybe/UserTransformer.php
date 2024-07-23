<?php

namespace App\Transformers\Api\Guest\Search\Vybe;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Services\Search\Vybe
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
