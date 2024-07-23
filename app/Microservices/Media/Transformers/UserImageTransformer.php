<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\UserImageResponse;
use App\Transformers\BaseTransformer;

/**
 * Class UserImageTransformer
 *
 * @package App\Transformers\Api
 */
class UserImageTransformer extends BaseTransformer
{
    /**
     * @param UserImageResponse $userImage
     *
     * @return array
     */
    public function transform(UserImageResponse $userImage) : array
    {
        return [
            'id'         => $userImage->id,
            'auth_id'    => $userImage->authId,
            'request_id' => $userImage->requestId,
            'url'        => $userImage->url,
            'url_min'    => $userImage->urlMin,
            'mime'       => $userImage->mime,
            'declined'   => $userImage->declined,
            'likes'      => $userImage->likes
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_image';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_images';
    }
}
