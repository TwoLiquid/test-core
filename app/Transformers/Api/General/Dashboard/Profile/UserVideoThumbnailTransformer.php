<?php

namespace App\Transformers\Api\General\Dashboard\Profile;

use App\Models\MySql\Media\UserVideoThumbnail;
use App\Transformers\BaseTransformer;

/**
 * Class UserVideoThumbnailTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Profile
 */
class UserVideoThumbnailTransformer extends BaseTransformer
{
    /**
     * @param UserVideoThumbnail $userVideoThumbnail
     *
     * @return array
     */
    public function transform(UserVideoThumbnail $userVideoThumbnail) : array
    {
        return [
            'id'      => $userVideoThumbnail->id,
            'url'     => $userVideoThumbnail->url,
            'url_min' => $userVideoThumbnail->url_min,
            'mime'    => $userVideoThumbnail->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_video_thumbnail';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_video_thumbnails';
    }
}
