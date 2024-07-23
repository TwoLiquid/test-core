<?php

namespace App\Repositories\Timezone\Interfaces;

use App\Models\MySql\Timezone\Timezone;
use App\Models\MySql\Timezone\TimezoneOffset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface TimezoneRepositoryInterface
 *
 * @package App\Repositories\Timezone\Interfaces
 */
interface TimezoneRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Timezone|null
     */
    public function findById(
        ?int $id
    ) : ?Timezone;

    /**
     * This method provides finding a single row
     *  with an eloquent model by certain query
     *
     * @param string $externalId
     *
     * @return Timezone|null
     */
    public function findByExternalId(
        string $externalId
    ) : ?Timezone;

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
     * with an eloquent model by certain query
     *
     * @return Collection
     */
    public function getAllHaveDst() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param array $ids
     *
     * @return Collection
     */
    public function getByIds(
        array $ids
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param string $externalId
     * @param bool $hasDst
     * @param bool $inDst
     *
     * @return Timezone|null
     */
    public function store(
        string $externalId,
        bool $hasDst,
        bool $inDst
    ) : ?Timezone;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Timezone $timezone
     * @param string|null $externalId
     * @param bool|null $hasDst
     * @param bool|null $inDst
     *
     * @return Timezone
     */
    public function update(
        Timezone $timezone,
        ?string $externalId,
        ?bool $hasDst,
        ?bool $inDst
    ) : Timezone;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Timezone $timezone
     * @param bool $inDst
     *
     * @return Timezone
     */
    public function updateInDst(
        Timezone $timezone,
        bool $inDst
    ) : Timezone;

    /**
     * This method provides attaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param Timezone $timezone
     * @param TimezoneOffset $timezoneOffset
     * @param bool $isDst
     *
     * @return void
     */
    public function attachOffset(
        Timezone $timezone,
        TimezoneOffset $timezoneOffset,
        bool $isDst
    ) : void;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param Timezone $timezone
     * @param TimezoneOffset $timezoneOffset
     *
     * @return void
     */
    public function detachOffset(
        Timezone $timezone,
        TimezoneOffset $timezoneOffset
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Timezone $timezone
     *
     * @return bool
     */
    public function delete(
        Timezone $timezone
    ) : bool;
}
