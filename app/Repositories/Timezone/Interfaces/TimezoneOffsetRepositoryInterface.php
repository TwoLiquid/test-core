<?php

namespace App\Repositories\Timezone\Interfaces;

use App\Models\MySql\Timezone\Timezone;
use App\Models\MySql\Timezone\TimezoneOffset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface TimezoneOffsetRepositoryInterface
 *
 * @package App\Repositories\Timezone\Interfaces
 */
interface TimezoneOffsetRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return TimezoneOffset|null
     */
    public function findById(
        ?int $id
    ) : ?TimezoneOffset;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param string $name
     *
     * @return TimezoneOffset|null
     */
    public function findByName(
        string $name
    ) : ?TimezoneOffset;

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
     * @param string $code
     * @param array $name
     * @param int $offset
     *
     * @return TimezoneOffset|null
     */
    public function store(
        string $code,
        array $name,
        int $offset
    ) : ?TimezoneOffset;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param TimezoneOffset $timezoneOffset
     * @param string|null $code
     * @param array|null $name
     * @param int|null $offset
     *
     * @return TimezoneOffset
     */
    public function update(
        TimezoneOffset $timezoneOffset,
        ?string $code,
        ?array $name,
        ?int $offset
    ) : TimezoneOffset;

    /**
     * This method provides attaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param TimezoneOffset $timezoneOffset
     * @param Timezone $timezone
     * @param bool $isDst
     *
     * @return void
     */
    public function attachTimezone(
        TimezoneOffset $timezoneOffset,
        Timezone $timezone,
        bool $isDst
    ) : void;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param TimezoneOffset $timezoneOffset
     * @param Timezone $timezone
     *
     * @return void
     */
    public function detachTimezone(
        TimezoneOffset $timezoneOffset,
        Timezone $timezone
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param TimezoneOffset $timezoneOffset
     *
     * @return bool
     */
    public function delete(
        TimezoneOffset $timezoneOffset
    ) : bool;
}
