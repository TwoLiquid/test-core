<?php

namespace App\Microservices\Media\Transformers;

use App\Microservices\Media\Responses\DeviceIconResponse;
use App\Transformers\BaseTransformer;

/**
 * Class DeviceIconTransformer
 *
 * @package App\Microservices\Media\Transformers
 */
class DeviceIconTransformer extends BaseTransformer
{
    /**
     * @param DeviceIconResponse $deviceIconResponse
     *
     * @return array
     */
    public function transform(DeviceIconResponse $deviceIconResponse) : array
    {
        return [
            'id'        => $deviceIconResponse->id,
            'device_id' => $deviceIconResponse->deviceId,
            'url'       => $deviceIconResponse->url,
            'mime'      => $deviceIconResponse->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'device_icon';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'device_icons';
    }
}
