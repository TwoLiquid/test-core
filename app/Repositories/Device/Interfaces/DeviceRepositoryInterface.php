<?php

namespace App\Repositories\Device\Interfaces;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Device;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface DeviceRepositoryInterface
 *
 * @package App\Repositories\Device\Interfaces
 */
interface DeviceRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Device|null
     */
    public function findById(
        ?int $id
    ) : ?Device;

    /**
     * This method provides getting a single row
     * with an eloquent model
     *
     * @param string $name
     *
     * @return Device|null
     */
    public function findByName(
        string $name
    ) : ?Device;

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
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param array $devicesIds
     *
     * @return Collection
     */
    public function getByIds(
        array $devicesIds
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param Activity $activity
     *
     * @return Collection
     */
    public function getByActivity(
        Activity $activity
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param string $name
     * @param bool|null $visible
     *
     * @return Device|null
     */
    public function store(
        string $name,
        ?bool $visible
    ) : ?Device;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Device $device
     * @param string|null $name
     * @param bool|null $visible
     *
     * @return Device
     */
    public function update(
        Device $device,
        ?string $name,
        ?bool $visible
    ) : Device;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param Device $device
     * @param Activity $activity
     */
    public function attachActivity(
        Device $device,
        Activity $activity
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param Device $device
     * @param array $activitiesIds
     * @param bool $detaching
     */
    public function attachActivities(
        Device $device,
        array $activitiesIds,
        bool $detaching
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param Device $device
     * @param Activity $activity
     */
    public function detachActivity(
        Device $device,
        Activity $activity
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param Device $device
     * @param array $activitiesIds
     */
    public function detachActivities(
        Device $device,
        array $activitiesIds
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param Device $device
     * @param Vybe $vybe
     */
    public function attachVybe(
        Device $device,
        Vybe $vybe
    ) : void;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param Device $device
     * @param array $vybesIds
     * @param bool $detaching
     */
    public function attachVybes(
        Device $device,
        array $vybesIds,
        bool $detaching
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param Device $device
     * @param Vybe $vybe
     */
    public function detachVybe(
        Device $device,
        Vybe $vybe
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param Device $device
     * @param array $vybesIds
     */
    public function detachVybes(
        Device $device,
        array $vybesIds
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Device $device
     *
     * @return bool
     */
    public function delete(
        Device $device
    ) : bool;
}
