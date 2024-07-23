<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\AdminAvatarResponse;
use App\Transformers\BaseTransformer;

/**
 * Class AdminAvatarTransformer
 *
 * @package App\Microservices\Media\Transformers
 */
class AdminAvatarTransformer extends BaseTransformer
{
    /**
     * @param AdminAvatarResponse $adminAvatarResponse
     *
     * @return array
     */
    public function transform(AdminAvatarResponse $adminAvatarResponse) : array
    {
        return [
            'id'      => $adminAvatarResponse->id,
            'auth_id' => $adminAvatarResponse->authId,
            'url'     => $adminAvatarResponse->url,
            'url_min' => $adminAvatarResponse->urlMin,
            'mime'    => $adminAvatarResponse->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'admin_avatar';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'admin_avatars';
    }
}
