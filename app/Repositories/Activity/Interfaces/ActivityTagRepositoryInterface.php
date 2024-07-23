<?php

namespace App\Repositories\Activity\Interfaces;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Activity\ActivityTag;
use App\Models\MySql\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ActivityTagRepositoryInterface
 *
 * @package App\Repositories\Activity\Interfaces
 */
interface ActivityTagRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return ActivityTag|null
     */
    public function findById(
        ?int $id
    ) : ?ActivityTag;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return ActivityTag|null
     */
    public function findFullForAdminById(
        ?int $id
    ) : ?ActivityTag;

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
     * @param Category|null $category
     * @param Category|null $subcategory
     *
     * @return Collection
     */
    public function getAllByCategories(
        ?Category $category,
        ?Category $subcategory
    ) : Collection;

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
     * @param Category|null $category
     * @param Category|null $subcategory
     *
     * @return Collection
     */
    public function getAllBySearchAndCategories(
        string $search,
        ?Category $category,
        ?Category $subcategory
    ) : Collection;

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
     * with an eloquent model with pagination
     *
     * @param Category|null $category
     * @param Category|null $subcategory
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllByCategoriesPaginated(
        ?Category $category,
        ?Category $subcategory,
        ?int $page,
        ?int $perPage
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
    public function getAllBySearchPaginated(
        string $search,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string $search
     * @param Category|null $category
     * @param Category|null $subcategory
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllBySearchAndCategoriesPaginated(
        string $search,
        ?Category $category,
        ?Category $subcategory,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param array $activityTagsIds
     *
     * @return Collection
     */
    public function getByIds(
        array $activityTagsIds
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Category|null $category
     * @param Category|null $subcategory
     * @param array $name
     * @param bool $visibleInCategory
     * @param bool $visibleInSubcategory
     *
     * @return ActivityTag|null
     */
    public function store(
        ?Category $category,
        ?Category $subcategory,
        array $name,
        bool $visibleInCategory,
        bool $visibleInSubcategory
    ) : ?ActivityTag;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param ActivityTag $activityTag
     * @param Category|null $category
     * @param Category|null $subcategory
     * @param array|null $name
     * @param bool|null $visibleInCategory
     * @param bool|null $visibleInSubcategory
     *
     * @return ActivityTag
     */
    public function update(
        ActivityTag $activityTag,
        ?Category $category,
        ?Category $subcategory,
        ?array $name,
        ?bool $visibleInCategory,
        ?bool $visibleInSubcategory
    ) : ActivityTag;

    /**
     * This method provides attaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param ActivityTag $activityTag
     * @param Activity $activity
     */
    public function attachActivity(
        ActivityTag $activityTag,
        Activity $activity
    ) : void;

    /**
     * This method provides attaching existing rows by related
     * entity repository with belonging to many relations
     *
     * @param ActivityTag $activityTag
     * @param array $activitiesIds
     * @param bool|null $detaching
     */
    public function attachActivities(
        ActivityTag $activityTag,
        array $activitiesIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param ActivityTag $activityTag
     * @param Activity $activity
     */
    public function detachActivity(
        ActivityTag $activityTag,
        Activity $activity
    ) : void;

    /**
     * This method provides detaching existing rows by related
     * entity repository with belonging to many relations
     *
     * @param ActivityTag $activityTag
     * @param Activity $activity
     */
    public function detachActivities(
        ActivityTag $activityTag,
        Activity $activity
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param ActivityTag $activityTag
     *
     * @return bool|null
     */
    public function delete(
        ActivityTag $activityTag
    ) : ?bool;
}
