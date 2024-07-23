<?php

namespace App\Services\Device;

use App\Exceptions\DatabaseException;
use App\Repositories\Device\DeviceRepository;
use App\Services\Device\Interfaces\DeviceServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DeviceService
 *
 * @package App\Services\Device
 */
class DeviceService implements DeviceServiceInterface
{
    /**
     * @var DeviceRepository
     */
    protected DeviceRepository $deviceRepository;

    /**
     * DeviceService constructor
     */
    public function __construct()
    {
        /** @var DeviceRepository deviceRepository */
        $this->deviceRepository = new DeviceRepository();
    }

    /**
     * @param array $devicesItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function updatePositions(
        array $devicesItems
    ) : Collection
    {
        $devices = new Collection();

        /** @var array $deviceItem */
        foreach ($devicesItems as $deviceItem) {

            /**
             * Getting device
             */
            $device = $this->deviceRepository->findById(
                $deviceItem['id']
            );

            /**
             * Add activity to a collection
             */
            $devices->add(
                $device
            );
        }

        return $devices;
    }
}
