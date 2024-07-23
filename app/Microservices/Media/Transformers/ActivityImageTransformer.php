<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\ActivityImageResponse;
use App\Transformers\BaseTransformer;

/**
 * Class ActivityImageTransformer
 *
 * @package App\Transformers\Api
 */
class ActivityImageTransformer extends BaseTransformer
{
    /**
     * @param ActivityImageResponse $activityImageResponse
     * 
     * @return array
     */
    public function transform(ActivityImageResponse $activityImageResponse) : array
    {
        return [
            'id'          => $activityImageResponse->id,
            'activity_id' => $activityImageResponse->activityId,
            'type'        => $activityImageResponse->type,
            'url'         => $activityImageResponse->url,
            'url_min'     => $activityImageResponse->urlMin,
            'mime'         => $activityImageResponse->mime
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
