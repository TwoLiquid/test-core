<?php

namespace App\Transformers\Api\Admin\General\IpRegistrationList;

use App\Models\MySql\IpRegistrationList;
use App\Transformers\BaseTransformer;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\Admin\General\IpRegistrationList
 */
class UserDuplicateTransformer extends BaseTransformer
{
    /**
     * @param IpRegistrationList $ipRegistrationList
     *
     * @return array
     */
    public function transform(IpRegistrationList $ipRegistrationList) : array
    {
        if ($ipRegistrationList->relationLoaded('user')) {
            $user = $ipRegistrationList->user;

            return [
                'id'       => $user->id,
                'auth_id'  => $user->auth_id,
                'username' => $user->username
            ];
        }

        return [];
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
