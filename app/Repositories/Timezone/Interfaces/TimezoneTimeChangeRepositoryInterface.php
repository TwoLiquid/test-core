<?php

namespace App\Repositories\Timezone\Interfaces;

use App\Models\MySql\Timezone\Timezone;
use App\Models\MySql\Timezone\TimezoneTimeChange;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface TimezoneTimeChangeRepositoryInterface
 *
 * @package App\Repositories\Timezone\Interfaces
 */
interface TimezoneTimeChangeRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return TimezoneTimeChange|null
     */
    public function findById(
        ?int $id
    ) : ?TimezoneTimeChange;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Timezone $timezone
     * @param Carbon $dateTime
     *
     * @return TimezoneTimeChange|null
     */
    public function findByTimezone(
        Timezone $timezone,
        Carbon $dateTime
    ) : ?TimezoneTimeChange;

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
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Timezone $timezone
     * @param bool $toDst
     * @param Carbon $startedAt
     * @param Carbon $completedAt
     *
     * @return TimezoneTimeChange|null
     */
    public function store(
        Timezone $timezone,
        bool $toDst,
        Carbon $startedAt,
        Carbon $completedAt
    ) : ?TimezoneTimeChange;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param TimezoneTimeChange $timezoneTimeChange
     * @param Timezone|null $timezone
     * @param bool|null $toDst
     * @param Carbon|null $startedAt
     * @param Carbon|null $completedAt
     *
     * @return TimezoneTimeChange
     */
    public function update(
        TimezoneTimeChange $timezoneTimeChange,
        ?Timezone $timezone,
        ?bool $toDst,
        ?Carbon $startedAt,
        ?Carbon $completedAt
    ) : TimezoneTimeChange;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param TimezoneTimeChange $timezoneTimeChange
     *
     * @return bool
     */
    public function delete(
        TimezoneTimeChange $timezoneTimeChange
    ) : bool;
}
