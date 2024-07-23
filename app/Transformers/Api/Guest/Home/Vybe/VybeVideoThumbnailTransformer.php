<?php

namespace App\Transformers\Api\Guest\Home\Vybe;

use App\Models\MySql\Media\VybeVideoThumbnail;
use App\Transformers\BaseTransformer;

/**
 * Class VybeVideoThumbnailTransformer
 *
 * @package App\Transformers\Api\Guest\Home\Vybe
 */
class VybeVideoThumbnailTransformer extends BaseTransformer
{
    /**
     * @param VybeVideoThumbnail $vybeVideoThumbnail
     *
     * @return array
     */
    public function transform(VybeVideoThumbnail $vybeVideoThumbnail) : array
    {
        return [
            'id'      => $vybeVideoThumbnail->id,
            'url'     => $vybeVideoThumbnail->url,
            'url_min' => $vybeVideoThumbnail->url_min,
            'mime'    => $vybeVideoThumbnail->mime
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
