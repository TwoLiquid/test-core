<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Lists\Vybe\Access\VybeAccessListItem;
use App\Lists\Vybe\Period\VybePeriodListItem;
use App\Lists\Vybe\Showcase\VybeShowcaseListItem;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Lists\Vybe\Step\VybeStepListItem;
use App\Lists\Vybe\Type\VybeTypeListItem;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitListItem;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptListItem;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Category;
use App\Models\MySql\Device;
use App\Models\MySql\Platform;
use App\Models\MySql\Unit;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface VybeRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Vybe|null
     */
    public function findById(
        ?int $id
    ) : ?Vybe;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Vybe|null
     */
    public function findFullById(
        ?int $id
    ) : ?Vybe;

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
     * @param string $search
     *
     * @return Collection
     */
    public function getAllBySearch(
        string $search
    ) : Collection;

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
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllForAdminPaginated(
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting data
     * with an eloquent model
     *
     * @param array $vybesIds
     *
     * @return Vybe
     */
    public function getStatusesByIdsCount(
        array $vybesIds
    ) : Vybe;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param int|null $vybeId
     * @param array|null $categoriesIds
     * @param array|null $subcategoriesIds
     * @param array|null $activitiesIds
     * @param array|null $typesIds
     * @param array|null $usersIds
     * @param string|null $vybeTitle
     * @param float|null $price
     * @param array|null $unitsIds
     * @param array|null $statusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFiltered(
        ?int $vybeId,
        ?array $categoriesIds,
        ?array $subcategoriesIds,
        ?array $activitiesIds,
        ?array $typesIds,
        ?array $usersIds,
        ?string $vybeTitle,
        ?float $price,
        ?array $unitsIds,
        ?array $statusesIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
     *
     * @param int|null $vybeId
     * @param array|null $categoriesIds
     * @param array|null $subcategoriesIds
     * @param array|null $activitiesIds
     * @param array|null $typesIds
     * @param array|null $usersIds
     * @param string|null $vybeTitle
     * @param float|null $price
     * @param array|null $unitsIds
     * @param array|null $statusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFiltered(
        ?int $vybeId,
        ?array $categoriesIds,
        ?array $subcategoriesIds,
        ?array $activitiesIds,
        ?array $typesIds,
        ?array $usersIds,
        ?string $vybeTitle,
        ?float $price,
        ?array $unitsIds,
        ?array $statusesIds,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param int|null $vybeId
     * @param array|null $categoriesIds
     * @param array|null $subcategoriesIds
     * @param array|null $activitiesIds
     * @param array|null $typesIds
     * @param array|null $usersIds
     * @param string|null $vybeTitle
     * @param float|null $price
     * @param array|null $unitsIds
     *
     * @return Collection
     */
    public function getAllFilteredForAdminLabels(
        ?int $vybeId,
        ?array $categoriesIds,
        ?array $subcategoriesIds,
        ?array $activitiesIds,
        ?array $typesIds,
        ?array $usersIds,
        ?string $vybeTitle,
        ?float $price,
        ?array $unitsIds
    ) : Collection;

    /**
     * This method provides getting data
     * with an eloquent model by certain query
     *
     * @param User|null $user
     * @param array|null $unitsIds
     * @param int|null $appearanceId
     * @param int|null $genderId
     * @param string|null $cityPlaceId
     * @param Category|null $category
     * @param int|null $subcategoryId
     * @param array|null $personalityTraitsIds
     * @param int|null $activityId
     * @param array|null $typesIds
     * @param array|null $devicesIds
     * @param array|null $platformsIds
     * @param array|null $languagesIds
     * @param array|null $tagsIds
     * @param string|null $dateMin
     * @param string|null $dateMax
     * @param float|null $priceMin
     * @param float|null $priceMax
     * @param int|null $sort
     * @param bool|null $hasAllTags
     *
     * @return Collection
     */
    public function getWithFiltersForCatalog(
        ?User $user,
        ?array $unitsIds,
        ?int $appearanceId,
        ?int $genderId,
        ?string $cityPlaceId,
        ?Category $category,
        ?int $subcategoryId,
        ?array $personalityTraitsIds,
        ?int $activityId,
        ?array $typesIds,
        ?array $devicesIds,
        ?array $platformsIds,
        ?array $languagesIds,
        ?array $tagsIds,
        ?string $dateMin,
        ?string $dateMax,
        ?float $priceMin,
        ?float $priceMax,
        ?int $sort,
        ?bool $hasAllTags
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user

     * @param array|null $typesIds
     * @param array|null $statusesIds
     *
     * @return Collection
     */
    public function getWithFiltersForDashboard(
        User $user,
        ?array $typesIds,
        ?array $statusesIds
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getUncompletedForDashboard(
        User $user
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param array|null $typesIds
     *
     * @return Collection
     */
    public function getFavoritesWithFiltersForDashboard(
        User $user,
        ?array $typesIds
    ) : Collection;

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
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getByUser(
        User $user
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param int|null $activityId
     * @param int|null $vybeSortId
     * @param array|null $vybeTypesIds
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getByUserPaginated(
        User $user,
        ?int $activityId,
        ?int $vybeSortId,
        ?array $vybeTypesIds,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getForAdminByUser(
        User $user
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getForAdminByUserPaginated(
        User $user,
        ?int $page ,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getFavoritesByUser(
        User $user
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getFavoritesByUserPaginated(
        User $user,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param string $search
     *
     * @return Collection
     */
    public function getFavoritesWithSearchByUser(
        User $user,
        string $search
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param string $search
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getFavoritesWithSearchByUserPaginated(
        User $user,
        string $search,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param array $vybesIds
     *
     * @return Collection
     */
    public function getByIds(
        array $vybesIds
    ) : Collection;

    /**
     * This method provides getting data
     * with an eloquent model
     *
     * @param User $user
     *
     * @return array
     */
    public function getFavoritesIdsByUser(
        User $user
    ) : array;

    /**
     * This method provides checking row existence
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     * @param User $user
     *
     * @return bool
     */
    public function isUserFavorite(
        Vybe $vybe,
        User $user
    ) : bool;

    /**
     * This method provides checking row existence
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     * @param User $user
     *
     * @return bool
     */
    public function belongsToUser(
        Vybe $vybe,
        User $user
    ) : bool;

    /**
     * @return Vybe|null
     */
    public function getLastVybe() : ?Vybe;

    /**
     * This method provides getting rows numbers
     * with an eloquent model by certain query
     *
     * @param array $vybesIds
     *
     * @return Vybe
     */
    public function getTypesByIdsCount(
        array $vybesIds
    ) : Vybe;

    /**
     * This method provides getting multiple rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getOrderedByUserVybesPaginated(
        User $user,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting multiple rows
     * with an eloquent model with pagination
     *
     * @param Collection $users
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getVybesByUsersPaginated(
        Collection $users,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting multiple rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getVybesNotDiscoveredPaginated(
        User $user,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting multiple rows
     * with an eloquent model with pagination
     *
     * @param User $user
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getVybesRecommendedForUserPaginated(
        User $user,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param Device $device
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getByDevicePaginated(
        Device $device,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model with pagination
     *
     * @param Platform $platform
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getByPlatformPaginated(
        Platform $platform,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     *
     *
     * @param Unit $unit
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getByUnitPaginated(
        Unit $unit,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param User $user
     * @param Activity|null $activity
     * @param VybeTypeListItem|null $vybeTypeListItem
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param VybeAccessListItem|null $vybeAccessListItem
     * @param string|null $vybeAccessPassword
     * @param VybeShowcaseListItem|null $vybeShowcaseListItem
     * @param VybeStatusListItem|null $vybeStatusListItem
     * @param VybeAgeLimitListItem|null $vybeAgeLimitListItem
     * @param VybeOrderAcceptListItem|null $vybeOrderAcceptListItem
     * @param string|null $title
     * @param int|null $userCount
     * @param int|null $orderAdvance
     * @param array|null $imagesIds
     * @param array|null $videosIds
     *
     * @return Vybe|null
     */
    public function store(
        User $user,
        ?Activity $activity,
        ?VybeTypeListItem $vybeTypeListItem,
        ?VybePeriodListItem $vybePeriodListItem,
        ?VybeAccessListItem $vybeAccessListItem,
        ?string $vybeAccessPassword,
        ?VybeShowcaseListItem $vybeShowcaseListItem,
        ?VybeStatusListItem $vybeStatusListItem,
        ?VybeAgeLimitListItem $vybeAgeLimitListItem,
        ?VybeOrderAcceptListItem $vybeOrderAcceptListItem,
        ?string $title,
        ?int $userCount,
        ?int $orderAdvance,
        ?array $imagesIds,
        ?array $videosIds
    ) : ?Vybe;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param Activity|null $activity
     * @param VybeTypeListItem|null $vybeTypeListItem
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param VybeAccessListItem|null $vybeAccessListItem
     * @param VybeShowcaseListItem|null $vybeShowcaseListItem
     * @param VybeStatusListItem|null $vybeStatusListItem
     * @param VybeAgeLimitListItem|null $vybeAgeLimitListItem
     * @param VybeOrderAcceptListItem|null $vybeOrderAcceptListItem
     * @param string|null $title
     * @param int|null $userCount
     * @param int|null $orderAdvance
     * @param array|null $imagesIds
     * @param array|null $videosIds
     *
     * @return Vybe
     */
    public function update(
        Vybe $vybe,
        ?Activity $activity,
        ?VybeTypeListItem $vybeTypeListItem,
        ?VybePeriodListItem $vybePeriodListItem,
        ?VybeAccessListItem $vybeAccessListItem,
        ?VybeShowcaseListItem $vybeShowcaseListItem,
        ?VybeStatusListItem $vybeStatusListItem,
        ?VybeAgeLimitListItem $vybeAgeLimitListItem,
        ?VybeOrderAcceptListItem $vybeOrderAcceptListItem,
        ?string $title,
        ?int $userCount,
        ?int $orderAdvance,
        ?array $imagesIds,
        ?array $videosIds
    ) : Vybe;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param Activity|null $activity
     * @param VybeTypeListItem|null $vybeTypeListItem
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param string|null $title
     * @param int|null $userCount
     *
     * @return Vybe
     */
    public function updateFirstStep(
        Vybe $vybe,
        ?Activity $activity,
        ?VybeTypeListItem $vybeTypeListItem,
        ?VybePeriodListItem $vybePeriodListItem,
        ?string $title,
        ?int $userCount
    ) : Vybe;

    /**
     * @param Vybe $vybe
     * @param VybeAccessListItem|null $vybeAccessListItem
     * @param VybeShowcaseListItem|null $vybeShowcaseListItem
     * @param VybeStatusListItem|null $vybeStatusListItem
     * @param VybeOrderAcceptListItem|null $vybeOrderAcceptListItem
     *
     * @return Vybe
     */
    public function updateFifthStep(
        Vybe $vybe,
        ?VybeAccessListItem $vybeAccessListItem,
        ?VybeShowcaseListItem $vybeShowcaseListItem,
        ?VybeStatusListItem $vybeStatusListItem,
        ?VybeOrderAcceptListItem $vybeOrderAcceptListItem
    ) : Vybe;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param Activity $activity
     *
     * @return Vybe
     */
    public function updateActivity(
        Vybe $vybe,
        Activity $activity
    ) : Vybe;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param VybeStatusListItem $vybeStatusListItem
     *
     * @return Vybe
     */
    public function updateStatus(
        Vybe $vybe,
        VybeStatusListItem $vybeStatusListItem
    ) : Vybe;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param array|null $imagesIds
     * @param array|null $videosIds
     *
     * @return Vybe
     */
    public function updateMediaIds(
        Vybe $vybe,
        ?array $imagesIds,
        ?array $videosIds
    ) : Vybe;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param VybeTypeListItem $vybeTypeListItem
     *
     * @return Vybe
     */
    public function updateType(
        Vybe $vybe,
        VybeTypeListItem $vybeTypeListItem
    ) : Vybe;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param VybeStepListItem $vybeStepListItem
     *
     * @return Vybe
     */
    public function updateStep(
        Vybe $vybe,
        VybeStepListItem $vybeStepListItem
    ) : Vybe;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param int $orderAdvance
     *
     * @return Vybe
     */
    public function updateOrderAdvance(
        Vybe $vybe,
        int $orderAdvance
    ) : Vybe;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param string|null $accessPassword
     *
     * @return Vybe
     */
    public function updateAccessPassword(
        Vybe $vybe,
        ?string $accessPassword
    ) : Vybe;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param VybeAgeLimitListItem $vybeAgeLimitListItem
     *
     * @return Vybe
     */
    public function updateAgeLimit(
        Vybe $vybe,
        VybeAgeLimitListItem $vybeAgeLimitListItem
    ) : Vybe;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     */
    public function updateRating(
        Vybe $vybe
    ) : void;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param string|null $suspendReason
     *
     * @return Vybe
     */
    public function updateSuspendReason(
        Vybe $vybe,
        ?string $suspendReason
    ) : Vybe;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     *
     * @return Vybe
     */
    public function increaseVersion(
        Vybe $vybe
    ) : Vybe;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param Vybe $vybe
     * @param Device $device
     */
    public function attachDevice(
        Vybe $vybe,
        Device $device
    ) : void;

    /**
     * This method provides attaching an existing models
     * with a current model with belongs to many relations
     *
     * @param Vybe $vybe
     * @param array $devicesIds
     * @param bool|null $detaching
     */
    public function attachDevices(
        Vybe $vybe,
        array $devicesIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param Vybe $vybe
     * @param Device $device
     */
    public function detachDevice(
        Vybe $vybe,
        Device $device
    ) : void;

    /**
     * This method provides detaching existing model
     * with a current model with belongs to many relations
     *
     * @param Vybe $vybe
     * @param array $devicesIds
     */
    public function detachDevices(
        Vybe $vybe,
        array $devicesIds
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     *
     * @return bool
     */
    public function delete(
        Vybe $vybe
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     *
     * @return bool
     */
    public function forceDelete(
        Vybe $vybe
    ) : bool;
}
