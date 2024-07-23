<?php

namespace App\Transformers\Api\General\Alert;

use App\Models\MySql\Media\AlertImage;
use App\Transformers\BaseTransformer;

/**
 * Class AlertImageTransformer
 *
 * @package App\Transformers\Api\General\Alert
 */
class AlertImageTransformer extends BaseTransformer
{
    /**
     * @param AlertImage $alertImage
     *
     * @return array
     */
    public function transform(AlertImage $alertImage) : array
    {
        return [
            'id'      => $alertImage->id,
            'url'     => $alertImage->url,
            'url_min' => $alertImage->url_min,
            'mime'    => $alertImage->mime,
            'active'  => $alertImage->active
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
