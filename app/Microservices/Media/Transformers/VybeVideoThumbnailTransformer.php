<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\VybeVideoThumbnailResponse;
use App\Transformers\BaseTransformer;

/**
 * Class VybeVideoThumbnailTransformer
 *
 * @package App\Transformers\Api
 */
class VybeVideoThumbnailTransformer extends BaseTransformer
{
    /**
     * @param VybeVideoThumbnailResponse $vybeVideoThumbnailResponse
     *
     * @return array
     */
    public function transform(VybeVideoThumbnailResponse $vybeVideoThumbnailResponse) : array
    {
        return [
            'id'       => $vybeVideoThumbnailResponse->id,
            'video_id' => $vybeVideoThumbnailResponse->videoId,
            'url'      => $vybeVideoThumbnailResponse->url,
            'mime'     => $vybeVideoThumbnailResponse->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_video_thumbnail';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_video_thumbnails';
    }
}
