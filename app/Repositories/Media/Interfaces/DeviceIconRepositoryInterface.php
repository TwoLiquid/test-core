<?php

namespace App\Repositories\Media\Interfaces;

use App\Models\MySql\Device;
use App\Models\MySql\Media\DeviceIcon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface DeviceIconRepositoryInterface
 *
 * @package App\Repositories\Media\Interfaces
 */
interface DeviceIconRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return DeviceIcon|null
     */
    public function findById(
        ?int $id
    ) : ?DeviceIcon;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param Device $device
     *
     * @return DeviceIcon|null
     */
    public function findByDevice(
        Device $device
    ) : ?DeviceIcon;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with a certain query
     *
     * @param Collection $devices
     *
     * @return Collection
     */
    public function getByDevices(
        Collection $devices
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param array $ids
     *
     * @return Collection
     */
    public function getByIds(
        array $ids
    ) : Collection;
}
