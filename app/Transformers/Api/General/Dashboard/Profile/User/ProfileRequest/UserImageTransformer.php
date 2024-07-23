<?php

namespace App\Transformers\Api\General\Dashboard\Profile\User\ProfileRequest;

use App\Models\MySql\Media\UserImage;
use App\Transformers\BaseTransformer;

/**
 * Class UserImageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Profile\User\ProfileRequest
 */
class UserImageTransformer extends BaseTransformer
{
    /**
     * @param UserImage $userImage
     *
     * @return array
     */
    public function transform(UserImage $userImage) : array
    {
        return [
            'id'       => $userImage->id,
            'url'      => $userImage->url,
            'url_min'  => $userImage->url_min,
            'mime'     => $userImage->mime,
            'declined' => $userImage->declined
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
