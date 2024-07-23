<?php

namespace App\Repositories\Unit\Interfaces;

use App\Lists\Unit\Type\UnitTypeListItem;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Category;
use App\Models\MySql\Unit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UnitRepositoryInterface
 *
 * @package App\Repositories\Unit\Interfaces
 */
interface UnitRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Unit|null
     */
    public function findById(
        ?int $id
    ) : ?Unit;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Unit|null
     */
    public function findFullForAdminById(
        ?int $id
    ) : ?Unit;

    /**
     * This method provides getting a single row
     * with an eloquent model
     *
     * @param string $name
     *
     * @return Unit|null
     */
    public function findByName(
        string $name
    ) : ?Unit;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAllEvent() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAllNotEvent() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param string $search
     *
     * @return Collection
     */
    public function getAllBySearch(
        string $search
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param string $search
     *
     * @return Collection
     */
    public function getAllEventBySearch(
        string $search
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param string $search
     *
     * @return Collection
     */
    public function getAllNotEventBySearch(
        string $search
    ) : Collection;

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
     * with an eloquent model with pagination
     *
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllEventPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllNotEventPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string $search
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllBySearchPaginated(
        string $search,
        ?int $page = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllEventBySearchPaginated(
        string $search,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllNotEventBySearchPaginated(
        string $search,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param Activity $activity
     *
     * @return Collection
     */
    public function getByActivity(
        Activity $activity
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param array $activitiesIds
     *
     * @return Collection
     */
    public function getByActivitiesIds(
        array $activitiesIds
    ) : Collection;

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
     * This method provides deleting existing rows
     * with an eloquent model
     *
     * @param Category $category
     * @param Collection $activities
     *
     * @return Collection
     */
    public function getByCategory(
        Category $category,
        Collection $activities
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param UnitTypeListItem $unitTypeListItem
     * @param array $name
     * @param int|null $duration
     * @param bool $visible
     *
     * @return Unit|null
     */
    public function store(
        UnitTypeListItem $unitTypeListItem,
        array $name,
        ?int $duration,
        bool $visible
    ) : ?Unit;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Unit $unit
     * @param UnitTypeListItem $unitTypeListItem
     * @param array|null $name
     * @param int|null $duration
     * @param bool|null $visible
     *
     * @return Unit
     */
    public function update(
        Unit $unit,
        UnitTypeListItem $unitTypeListItem,
        ?array $name,
        ?int $duration,
        ?bool $visible
    ) : Unit;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Unit $unit
     *
     * @return bool
     */
    public function delete(
        Unit $unit
    ) : bool;
}
