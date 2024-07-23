<?php

namespace App\Transformers\Api\Admin\Csau\Device;

use App\Models\MySql\Device;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class DeviceWithPaginationTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Device
 */
class DeviceWithPaginationTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $deviceIcons;

    /**
     * @var int
     */
    protected int $page;

    /**
     * @var int
     */
    protected int $perPage;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'icon',
        'vybes'
    ];

    /**
     * DeviceWithPaginationTransformer constructor
     *
     * @param Collection|null $deviceIcons
     * @param int $perPage
     * @param int $page
     */
    public function __construct(
        Collection $deviceIcons = null,
        int $page = 1,
        int $perPage = 18
    )
    {
        /** @var Collection deviceIcons */
        $this->deviceIcons = $deviceIcons;

        /** @var int page */
        $this->page = $page;

        /** @var int perPage */
        $this->perPage = $perPage;
    }

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
     * @return Item|null
     */
    public function includeVybes(Device $device) : ?Item
    {
        $vybes = null;

        if ($device->relationLoaded('vybes')) {
            $vybes = $device->vybes;
        }

        return $vybes ?
            $this->item(
                $vybes,
                new PaginatedVybeTransformer(
                    $this->page,
                    $this->perPage
                )
            ) : null;
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
