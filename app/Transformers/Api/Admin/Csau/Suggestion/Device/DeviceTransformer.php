<?php

namespace App\Transformers\Api\Admin\Csau\Suggestion\Device;

use App\Models\MySql\Device;
use App\Transformers\BaseTransformer;

/**
 * Class DeviceTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Suggestion\Device
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
            'id'   => $device->id,
            'code' => $device->code,
            'name' => $device->name
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
