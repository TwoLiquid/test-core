<?php

namespace App\Repositories\Timeslot\Interfaces;

use App\Models\MySql\Timeslot;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface TimeslotRepositoryInterface
 *
 * @package App\Repositories\Timeslot\Interfaces
 */
interface TimeslotRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Timeslot|null
     */
    public function findById(
        ?int $id
    ) : ?Timeslot;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     * @param string $datetimeFrom
     * @param string $datetimeTo
     *
     * @return Timeslot|null
     */
    public function findForVybeBetweenDates(
        Vybe $vybe,
        string $datetimeFrom,
        string $datetimeTo
    ) : ?Timeslot;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     * @param string $datetimeFrom
     * @param string $datetimeTo
     *
     * @return Timeslot|null
     */
    public function findForVybeByDates(
        Vybe $vybe,
        string $datetimeFrom,
        string $datetimeTo
    ) : ?Timeslot;

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
     * This method provides getting all rows
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param Carbon $datetimeFrom
     * @param Carbon $datetimeTo
     *
     * @return Collection
     */
    public function getForVybeBetweenDates(
        Vybe $vybe,
        Carbon $datetimeFrom,
        Carbon $datetimeTo
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @param Vybe $vybe
     *
     * @return Collection
     */
    public function getFutureForVybe(
        Vybe $vybe
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param string $datetimeFrom
     * @param string $datetimeTo
     *
     * @return Timeslot|null
     */
    public function store(
        string $datetimeFrom,
        string $datetimeTo
    ) : ?Timeslot;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param Timeslot $timeslot
     * @param string $datetimeFrom
     * @param string $datetimeTo
     *
     * @return Timeslot
     */
    public function update(
        Timeslot $timeslot,
        string $datetimeFrom,
        string $datetimeTo
    ) : Timeslot;

    /**
     * This method provides attaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param Timeslot $timeslot
     * @param User $user
     */
    public function attachUser(
        Timeslot $timeslot,
        User $user
    ) : void;

    /**
     * This method provides attaching existing rows by related
     * entity repository with belonging to many relations
     *
     * @param Timeslot $timeslot
     * @param array $usersIds
     * @param bool|null $detaching
     */
    public function attachUsers(
        Timeslot $timeslot,
        array $usersIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param Timeslot $timeslot
     * @param User $user
     */
    public function detachUser(
        Timeslot $timeslot,
        User $user
    ) : void;

    /**
     * This method provides detaching existing rows by related
     * entity repository with belonging to many relations
     *
     * @param Timeslot $timeslot
     * @param array $usersIds
     */
    public function detachUsers(
        Timeslot $timeslot,
        array $usersIds
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Timeslot $timeslot
     *
     * @return bool
     */
    public function delete(
        Timeslot $timeslot
    ) : bool;
}
