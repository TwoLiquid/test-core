<?php

namespace App\Services\Device\Interfaces;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface DeviceServiceInterface
 *
 * @package App\Services\Device\Interfaces
 */
interface DeviceServiceInterface
{
    /**
     * This method provides updating data
     *
     * @param array $devicesItems
     *
     * @return Collection
     */
    public function updatePositions(
        array $devicesItems
    ) : Collection;
}