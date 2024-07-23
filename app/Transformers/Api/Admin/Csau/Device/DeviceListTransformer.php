<?php

namespace App\Transformers\Api\Admin\Csau\Device;

use App\Models\MySql\Device;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class DeviceListTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Device
 */
class DeviceListTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $deviceIcons;

    /**
     * DeviceListTransformer constructor
     *
     * @param Collection|null $deviceIcons
     */
    public function __construct(
        Collection $deviceIcons = null
    )
    {
        /** @var Collection deviceIcons */
        $this->deviceIcons = $deviceIcons;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'icon'
    ];

    /**
     * @param Device $device
     *
     * @return array
     */
    public function transform(Device $device) : array
    {
        return [
            'id'          => $device->id,
            'name'        => $device->name,
            'code'        => $device->code,
            'visible'     => $device->visible,
            'vybes_count' => $device->vybes_count
        ];
    }

    /**
     * @param Device $device
     *
     * @return Item|null
     */
    public function includeIcon(Device $device) : ?Item
    {
        $deviceIcon = $this->deviceIcons?->filter(function ($item) use ($device) {
            return $item->device_id == $device->id;
        })->first();

        return $deviceIcon ? $this->item($deviceIcon, new DeviceIconTransformer) : null;
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
