<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\UserVideoThumbnailResponse;
use App\Transformers\BaseTransformer;

/**
 * Class UserVideoThumbnailTransformer
 *
 * @package App\Transformers\Api
 */
class UserVideoThumbnailTransformer extends BaseTransformer
{
    /**
     * @param UserVideoThumbnailResponse $userVideoThumbnail
     *
     * @return array
     */
    public function transform(UserVideoThumbnailResponse $userVideoThumbnail) : array
    {
        return [
            'id'       => $userVideoThumbnail->id,
            'video_id' => $userVideoThumbnail->videoId,
            'url'      => $userVideoThumbnail->url,
            'url_min'  => $userVideoThumbnail->urlMin,
            'mime'     => $userVideoThumbnail->mime
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
