<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\AlertImageResponse;
use App\Transformers\BaseTransformer;

/**
 * Class AlertImageTransformer
 *
 * @package App\Microservices\Media\Transformers
 */
class AlertImageTransformer extends BaseTransformer
{
    /**
     * @param AlertImageResponse $alertImageResponse
     *
     * @return array
     */
    public function transform(AlertImageResponse $alertImageResponse) : array
    {
        return [
            'id'       => $alertImageResponse->id,
            'alert_id' => $alertImageResponse->alertId,
            'url'      => $alertImageResponse->url,
            'url_min'  => $alertImageResponse->urlMin,
            'mime'     => $alertImageResponse->mime,
            'active'   => $alertImageResponse->active
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'alert_image';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'alert_images';
    }
}
