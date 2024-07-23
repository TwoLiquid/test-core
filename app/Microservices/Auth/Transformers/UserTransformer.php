<?php

namespace App\Microservices\Auth\Transformers;

use App\Microservices\Auth\Responses\UserResponse;
use App\Transformers\BaseTransformer;

/**
 * Class UserTransformer
 *
 * @package App\Microservices\Auth\Transformers
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @param UserResponse $userResponse
     *
     * @return array
     */
    public function transform(UserResponse $userResponse) : array
    {
        return [
            'id'       => $userResponse->id,
            'is_admin' => $userResponse->isAdmin,
            'token'    => $userResponse->token
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
        return 'user';
    }
}
