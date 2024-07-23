<?php

namespace App\Transformers\Api\Guest\Profile\Home;

use App\Models\MySql\Media\ActivityImage;
use App\Transformers\BaseTransformer;

/**
 * Class ActivityImageTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home
 */
class ActivityImageTransformer extends BaseTransformer
{
    /**
     * @param ActivityImage $activityImage
     *
     * @return array
     */
    public function transform(ActivityImage $activityImage) : array
    {
        return [
            'id'      => $activityImage->id,
            'type'    => $activityImage->type,
            'url'     => $activityImage->url,
            'url_min' => $activityImage->url_min,
            'mime'    => $activityImage->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'activity_image';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'activity_images';
    }
}
