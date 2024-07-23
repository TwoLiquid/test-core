<?php

namespace App\Repositories\Schedule\Interfaces;

use App\Models\MySql\Schedule;
use App\Models\MySql\Vybe\Vybe;

/**
 * Interface ScheduleRepositoryInterface
 *
 * @package App\Repositories\Schedule\Interfaces
 */
interface ScheduleRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Schedule|null
     */
    public function findById(
        ?int $id
    ) : ?Schedule;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     * @param string $dateTimeFrom
     * @param string $dateTimeTo
     *
     * @return Schedule|null
     */
    public function findByDatesForVybe(
        Vybe $vybe,
        string $dateTimeFrom,
        string $dateTimeTo
    ) : ?Schedule;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param string $dateTimeFrom
     * @param string $dateTimeTo
     *
     * @return Schedule|null
     */
    public function store(
        Vybe $vybe,
        string $dateTimeFrom,
        string $dateTimeTo
    ) : ?Schedule;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param Schedule $schedule
     * @param Vybe|null $vybe
     * @param string|null $dateTimeFrom
     * @param string|null $dateTimeTo
     *
     * @return Schedule
     */
    public function update(
        Schedule $schedule,
        ?Vybe $vybe,
        ?string $dateTimeFrom,
        ?string $dateTimeTo
    ) : Schedule;

    /**
     * This method provides deleting existing row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return bool
     */
    public function deleteForVybe(
        Vybe $vybe
    ) : bool;

    /**
     * This method provides deleting existing rows
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return bool
     */
    public function deleteForceForVybe(
        Vybe $vybe
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Schedule $schedule
     *
     * @return bool
     */
    public function delete(
        Schedule $schedule
    ) : bool;
}
