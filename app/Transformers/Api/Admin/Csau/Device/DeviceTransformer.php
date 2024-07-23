<?php

namespace App\Transformers\Api\Admin\Csau\Device;

use App\Models\MySql\Device;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class DeviceTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Device
 */
class DeviceTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $deviceIcons;

    /**
     * DeviceTransformer constructor
     *
     * @param EloquentCollection|null $deviceIcons
     */
    public function __construct(
        EloquentCollection $deviceIcons = null
    )
    {
        /** @var EloquentCollection deviceIcons */
        $this->deviceIcons = $deviceIcons;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'icon',
        'vybes'
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
     * @param Device $device
     *
     * @return Collection|null
     */
    public function includeVybes(Device $device) : ?Collection
    {
        $vybes = null;

        if ($device->relationLoaded('vybes')) {
            $vybes = $device->vybes;
        }

        return $vybes ? $this->collection($vybes, new VybeTransformer) : null;
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
