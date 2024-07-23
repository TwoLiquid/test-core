<?php

namespace App\Transformers\Api\Guest\User\User\Vybe;

use App\Models\MySql\Device;
use App\Transformers\BaseTransformer;

/**
 * Class DeviceTransformer
 *
 * @package App\Transformers\Api\Guest\User\User\Vybe
 */
class DeviceTransformer extends BaseTransformer
{
    /**
     * @param Device $device
     *
     * @return array
     */
    public function transform(Device $device) : array
    {
        return [
            'id'      => $device->id,
            'name'    => $device->name,
            'code'    => $device->code,
            'visible' => $device->visible
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'device';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'devices';
    }
}
