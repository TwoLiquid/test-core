<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\Vybe\Access\VybeAccessListItem;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitListItem;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptListItem;
use App\Lists\Vybe\Period\VybePeriodListItem;
use App\Lists\Vybe\Showcase\VybeShowcaseListItem;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Lists\Vybe\Type\VybeTypeListItem;
use App\Models\MongoDb\Suggestion\DeviceSuggestion;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Category;
use App\Models\MySql\Device;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeChangeRequestRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeChangeRequestRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeChangeRequest|null
     */
    public function findById(
        ?string $id
    ) : ?VybeChangeRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return VybeChangeRequest|null
     */
    public function findPendingForVybe(
        Vybe $vybe
    ) : ?VybeChangeRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return VybeChangeRequest|null
     */
    public function findLastForVybe(
        Vybe $vybe
    ) : ?VybeChangeRequest;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllCount() : int;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param RequestStatusListItem|null $requestStatusListItem
     *
     * @return Collection
     */
    public function getAllWithStatus(
        ?RequestStatusListItem $requestStatusListItem
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @param string|null $requestId
     * @param int|null $vybeVersion
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $sales
     * @param array|null $languagesIds
     * @param array|null $vybeStatusesIds
     * @param array|null $requestStatusesIds
     * @param string|null $admin
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFiltered(
        ?string $requestId,
        ?int $vybeVersion,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $sales,
        ?array $languagesIds,
        ?array $vybeStatusesIds,
        ?array $requestStatusesIds,
        ?string $admin,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string|null $requestId
     * @param int|null $vybeVersion
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $sales
     * @param array|null $languagesIds
     * @param array|null $vybeStatusesIds
     * @param array|null $requestStatusesIds
     * @param string|null $admin
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFiltered(
        ?string $requestId,
        ?int $vybeVersion,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $sales,
        ?array $languagesIds,
        ?array $vybeStatusesIds,
        ?array $requestStatusesIds,
        ?string $admin,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows number
     * with an eloquent model
     *
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return int
     */
    public function getRequestStatusCount(
        RequestStatusListItem $requestStatusListItem
    ) : int;

    /**
     * This method provides getting rows number
     * with an eloquent model
     *
     * @param array $ids
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return int
     */
    public function getRequestStatusCountByIds(
        array $ids,
        RequestStatusListItem $requestStatusListItem
    ) : int;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Vybe|null $vybe
     * @param string|null $title
     * @param string|null $previousTitle
     * @param Category|null $category
     * @param Category|null $previousCategory
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param Category|null $previousSubcategory
     * @param string|null $subcategorySuggestion
     * @param Activity|null $activity
     * @param Activity|null $previousActivity
     * @param string|null $activitySuggestion
     * @param array|null $devicesIds
     * @param array|null $previousDevicesIds
     * @param string|null $deviceSuggestion
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param VybePeriodListItem|null $previousVybePeriodListItem
     * @param int|null $userCount
     * @param int|null $previousUserCount
     * @param VybeTypeListItem|null $vybeTypeListItem
     * @param VybeTypeListItem|null $previousVybeTypeListItem
     * @param int|null $orderAdvance
     * @param int|null $previousOrderAdvance
     * @param array|null $imagesIds
     * @param array|null $previousImagesIds
     * @param array|null $videosIds
     * @param array|null $previousVideosIds
     * @param VybeAccessListItem|null $vybeAccessListItem
     * @param VybeAccessListItem|null $previousVybeAccessListItem
     * @param VybeShowcaseListItem|null $vybeShowcaseListItem
     * @param VybeShowcaseListItem|null $previousVybeShowcaseListItem
     * @param VybeOrderAcceptListItem|null $vybeOrderAcceptListItem
     * @param VybeOrderAcceptListItem|null $previousVybeOrderAcceptListItem
     * @param VybeStatusListItem|null $vybeStatusListItem
     * @param VybeStatusListItem|null $previousVybeStatusListItem
     *
     * @return VybeChangeRequest|null
     */
    public function store(
        ?Vybe $vybe,
        ?string $title,
        ?string $previousTitle,
        ?Category $category,
        ?Category $previousCategory,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?Category $previousSubcategory,
        ?string $subcategorySuggestion,
        ?Activity $activity,
        ?Activity $previousActivity,
        ?string $activitySuggestion,
        ?array $devicesIds,
        ?array $previousDevicesIds,
        ?string $deviceSuggestion,
        ?VybePeriodListItem $vybePeriodListItem,
        ?VybePeriodListItem $previousVybePeriodListItem,
        ?int $userCount,
        ?int $previousUserCount,
        ?VybeTypeListItem $vybeTypeListItem,
        ?VybeTypeListItem $previousVybeTypeListItem,
        ?int $orderAdvance,
        ?int $previousOrderAdvance,
        ?array $imagesIds,
        ?array $previousImagesIds,
        ?array $videosIds,
        ?array $previousVideosIds,
        ?VybeAccessListItem $vybeAccessListItem,
        ?VybeAccessListItem $previousVybeAccessListItem,
        ?VybeShowcaseListItem $vybeShowcaseListItem,
        ?VybeShowcaseListItem $previousVybeShowcaseListItem,
        ?VybeOrderAcceptListItem $vybeOrderAcceptListItem,
        ?VybeOrderAcceptListItem $previousVybeOrderAcceptListItem,
        ?VybeStatusListItem $vybeStatusListItem,
        ?VybeStatusListItem $previousVybeStatusListItem
    ) : ?VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Category $category
     *
     * @return VybeChangeRequest|null
     */
    public function updateSuggestedCategory(
        VybeChangeRequest $vybeChangeRequest,
        Category $category
    ) : ?VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Category $subcategory
     *
     * @return VybeChangeRequest|null
     */
    public function updateSuggestedSubcategory(
        VybeChangeRequest $vybeChangeRequest,
        Category $subcategory
    ) : ?VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Activity $activity
     *
     * @return VybeChangeRequest|null
     */
    public function updateSuggestedActivity(
        VybeChangeRequest $vybeChangeRequest,
        Activity $activity
    ) : ?VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Device $device
     *
     * @return VybeChangeRequest
     */
    public function updateSuggestedDevice(
        VybeChangeRequest $vybeChangeRequest,
        Device $device
    ) : VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return VybeChangeRequest|null
     */
    public function updateDeviceSuggestion(
        VybeChangeRequest $vybeChangeRequest,
        DeviceSuggestion $deviceSuggestion
    ) : ?VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param VybeAgeLimitListItem|null $vybeAgeLimitListItem
     *
     * @return VybeChangeRequest|null
     */
    public function updateAgeLimit(
        VybeChangeRequest $vybeChangeRequest,
        ?VybeAgeLimitListItem $vybeAgeLimitListItem
    ) : ?VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param RequestFieldStatusListItem|null $schedulesStatus
     *
     * @return VybeChangeRequest|null
     */
    public function updateSchedulesStatus(
        VybeChangeRequest $vybeChangeRequest,
        ?RequestFieldStatusListItem $schedulesStatus
    ) : ?VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param bool $shown
     *
     * @return VybeChangeRequest
     */
    public function updateShown(
        VybeChangeRequest $vybeChangeRequest,
        bool $shown
    ) : VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param RequestFieldStatusListItem|null $titleStatus
     * @param RequestFieldStatusListItem|null $categoryStatus
     * @param RequestFieldStatusListItem|null $subcategoryStatus
     * @param RequestFieldStatusListItem|null $devicesStatus
     * @param RequestFieldStatusListItem|null $activityStatus
     * @param RequestFieldStatusListItem|null $periodStatus
     * @param RequestFieldStatusListItem|null $userCountStatus
     * @param RequestFieldStatusListItem|null $schedulesStatus
     * @param RequestFieldStatusListItem|null $orderAdvanceStatus
     * @param RequestFieldStatusListItem|null $accessStatus
     * @param RequestFieldStatusListItem|null $showcaseStatus
     * @param RequestFieldStatusListItem|null $orderAcceptStatus
     * @param RequestFieldStatusListItem|null $statusStatus
     *
     * @return VybeChangeRequest|null
     */
    public function updateStatuses(
        VybeChangeRequest $vybeChangeRequest,
        ?RequestFieldStatusListItem $titleStatus,
        ?RequestFieldStatusListItem $categoryStatus,
        ?RequestFieldStatusListItem $subcategoryStatus,
        ?RequestFieldStatusListItem $devicesStatus,
        ?RequestFieldStatusListItem $activityStatus,
        ?RequestFieldStatusListItem $periodStatus,
        ?RequestFieldStatusListItem $userCountStatus,
        ?RequestFieldStatusListItem $schedulesStatus,
        ?RequestFieldStatusListItem $orderAdvanceStatus,
        ?RequestFieldStatusListItem $accessStatus,
        ?RequestFieldStatusListItem $showcaseStatus,
        ?RequestFieldStatusListItem $orderAcceptStatus,
        ?RequestFieldStatusListItem $statusStatus
    ) : ?VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return VybeChangeRequest|null
     */
    public function updateStatus(
        VybeChangeRequest $vybeChangeRequest,
        RequestStatusListItem $requestStatusListItem
    ) : ?VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param string|null $toastMessageText
     *
     * @return VybeChangeRequest|null
     */
    public function updateToastMessageText(
        VybeChangeRequest $vybeChangeRequest,
        ?string $toastMessageText
    ) : ?VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param LanguageListItem $languageListItem
     *
     * @return VybeChangeRequest
     */
    public function updateLanguage(
        VybeChangeRequest $vybeChangeRequest,
        LanguageListItem $languageListItem
    ) : VybeChangeRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Admin $admin
     *
     * @return VybeChangeRequest
     */
    public function updateAdmin(
        VybeChangeRequest $vybeChangeRequest,
        Admin $admin
    ) : VybeChangeRequest;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return bool
     */
    public function delete(
        VybeChangeRequest $vybeChangeRequest
    ) : bool;
}
