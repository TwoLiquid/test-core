<?php

namespace App\Repositories\Activity\Interfaces;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Activity\ActivityTag;
use App\Models\MySql\Category;
use App\Models\MySql\Device;
use App\Models\MySql\Platform;
use App\Models\MySql\Unit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface ActivityRepositoryInterface
 *
 * @package App\Repositories\Activity\Interfaces
 */
interface ActivityRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Activity|null
     */
    public function findById(
        ?int $id
    ) : ?Activity;

    /**
     * This method provides getting a single row
     * with an eloquent model by certain query
     *
     * @param Category $category
     * @param string $name
     *
     * @return Activity|null
     */
    public function findByName(
        Category $category,
        string $name
    ) : ?Activity;

    /**
     * This method provides getting a single row
     * with an eloquent model by certain query
     *
     * @param string|null $code
     *
     * @return Activity|null
     */
    public function findByCode(
        ?string $code
    ) : ?Activity;

    /**
     * This method provides finding existing row
     * with an eloquent model
     *
     * @param int|null $id
     *
     * @return Activity|null
     */
    public function findWithUnits(
        ?int $id
    ) : ?Activity;

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
    public function getPopularActivities() : Collection;

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
     * This method provides getting rows
     * with an eloquent model
     *
     * @param Category $category
     *
     * @return Collection
     */
    public function getByCategory(
        Category $category
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param Category $category
     *
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getByCategoryPaginated(
        Category $category,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @param Category $category
     *
     * @return Collection
     */
    public function getAllByCategory(
        Category $category
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param Category $category
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllByCategoryPaginated(
        Category $category,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @param Category $category
     * @param string $search
     *
     * @return Collection
     */
    public function getAllByCategoryWithSearch(
        Category $category,
        string $search
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param Category $category
     * @param string $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllByCategoryWithSearchPaginated(
        Category $category,
        string $search,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param array|null $categoriesIds
     * @param string|null $name
     *
     * @return Collection
     */
    public function getByCategoriesIds(
        ?array $categoriesIds,
        ?string $name
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param Device $device
     * @param string|null $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getByDevicePaginated(
        Device $device,
        ?string $search,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param Platform $platform
     * @param string|null $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getByPlatformPaginated(
        Platform $platform,
        ?string $search,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param Activity $activity
     *
     * @return Activity|null
     */
    public function findRelatedActivity(
        Activity $activity
    ) : ?Activity;

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
     * @param string $search
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllBySearchPaginated(
        string $search,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting limited rows
     * with an eloquent model by certain query
     *
     * @param string $search
     * @param int|null $limit
     *
     * @return Collection
     */
    public function getWithGlobalSearchByLimit(
        string $search,
        ?int $limit
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param array $activitiesIds
     *
     * @return Collection
     */
    public function getByIds(
        array $activitiesIds
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Category $category
     * @param array $name
     * @param bool|null $visible
     * @param int|null $position
     *
     * @return Activity|null
     */
    public function store(
        Category $category,
        array $name,
        ?bool $visible,
        ?int $position
    ) : ?Activity;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Activity $activity
     * @param Category|null $category
     * @param array|null $name
     * @param bool|null $visible
     * @param int|null $position
     *
     * @return Activity
     */
    public function update(
        Activity $activity,
        ?Category $category,
        ?array $name,
        ?bool $visible,
        ?int $position
    ) : Activity;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Activity $activity
     * @param int $position
     */
    public function updatePosition(
        Activity $activity,
        int $position
    ) : void;

    /**
     * This method provides updating existing rows
     * with an eloquent model
     *
     * @param Activity $activity
     * @param array $unitsItems
     *
     * @return Collection
     */
    public function updateUnitPositions(
        Activity $activity,
        array $unitsItems
    ) : Collection;

    /**
     * This method provides attaching existing row
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param Device $device
     */
    public function attachDevice(
        Activity $activity,
        Device $device
    ) : void;

    /**
     * This method provides attaching existing rows
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param array $devicesIds
     * @param bool|null $detaching
     */
    public function attachDevices(
        Activity $activity,
        array $devicesIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing row
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param Device $device
     */
    public function detachDevice(
        Activity $activity,
        Device $device
    ) : void;

    /**
     * This method provides detaching existing rows
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param array $devicesIds
     */
    public function detachDevices(
        Activity $activity,
        array $devicesIds
    ) : void;

    /**
     * This method provides attaching existing row
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param Platform $platform
     */
    public function attachPlatform(
        Activity $activity,
        Platform $platform
    ) : void;

    /**
     * This method provides attaching existing rows
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param array $platformsIds
     * @param bool|null $detaching
     */
    public function attachPlatforms(
        Activity $activity,
        array $platformsIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing row
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param Platform $platform
     */
    public function detachPlatform(
        Activity $activity,
        Platform $platform
    ) : void;

    /**
     * This method provides detaching existing rows
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param array $platformsIds
     */
    public function detachPlatforms(
        Activity $activity,
        array $platformsIds
    ) : void;

    /**
     * This method provides attaching existing row
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param ActivityTag $activityTag
     */
    public function attachActivityTag(
        Activity $activity,
        ActivityTag $activityTag
    ) : void;

    /**
     * This method provides attaching existing rows
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param array $activityTagsIds
     * @param bool|null $detaching
     */
    public function attachActivityTags(
        Activity $activity,
        array $activityTagsIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing row
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param ActivityTag $activityTag
     */
    public function detachActivityTag(
        Activity $activity,
        ActivityTag $activityTag
    ) : void;

    /**
     * This method provides detaching existing rows
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param array $activityTagsIds
     */
    public function detachActivityTags(
        Activity $activity,
        array $activityTagsIds
    ) : void;

    /**
     * This method provides attaching existing row
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param Unit $unit
     * @param bool $visible
     * @param int|null $position
     */
    public function attachUnit(
        Activity $activity,
        Unit $unit,
        bool $visible,
        ?int $position
    ) : void;

    /**
     * This method provides attaching existing rows
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param array $unitsItems
     * @param bool $detaching
     */
    public function attachUnits(
        Activity $activity,
        array $unitsItems,
        bool $detaching
    ) : void;

    /**
     * This method provides detaching existing row
     * with a current model with belongs to many relations
     *
     * @param Activity $activity
     * @param Unit $unit
     */
    public function detachUnit(
        Activity $activity,
        Unit $unit
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Activity $activity
     *
     * @return bool|null
     */
    public function delete(
        Activity $activity
    ) : ?bool;
}
