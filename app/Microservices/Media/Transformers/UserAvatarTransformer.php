<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\UserAvatarResponse;
use App\Transformers\BaseTransformer;

/**
 * Class UserAvatarTransformer
 *
 * @package App\Transformers\Api
 */
class UserAvatarTransformer extends BaseTransformer
{
    /**
     * @param UserAvatarResponse $userAvatar
     *
     * @return array
     */
    public function transform(UserAvatarResponse $userAvatar) : array
    {
        return [
            'id'         => $userAvatar->id,
            'auth_id'    => $userAvatar->authId,
            'request_id' => $userAvatar->requestId,
            'url'        => $userAvatar->url,
            'url_min'    => $userAvatar->urlMin,
            'mime'       => $userAvatar->mime,
            'declined'   => $userAvatar->declined
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_avatar';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_avatars';
    }
}
