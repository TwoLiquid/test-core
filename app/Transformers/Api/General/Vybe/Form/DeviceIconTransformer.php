<?php

namespace App\Transformers\Api\General\Vybe\Form;

use App\Models\MySql\Media\DeviceIcon;
use App\Transformers\BaseTransformer;

/**
 * Class DeviceIconTransformer
 *
 * @package App\Transformers\Api\General\Vybe\Form
 */
class DeviceIconTransformer extends BaseTransformer
{
    /**
     * @param DeviceIcon $deviceIcon
     *
     * @return array
     */
    public function transform(DeviceIcon $deviceIcon) : array
    {
        return [
            'id'   => $deviceIcon->id,
            'url'  => $deviceIcon->url,
            'mime' => $deviceIcon->mime
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
