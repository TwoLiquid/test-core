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
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Category;
use App\Models\MySql\Device;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybePublishRequestRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybePublishRequestRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybePublishRequest|null
     */
    public function findById(
        ?string $id
    ) : ?VybePublishRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return VybePublishRequest|null
     */
    public function findPendingForVybe(
        Vybe $vybe
    ) : ?VybePublishRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return VybePublishRequest|null
     */
    public function findLastForVybe(
        Vybe $vybe
    ) : ?VybePublishRequest;

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
     * @param Vybe $vybe
     * @param string $title
     * @param Category|null $category
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param string|null $subcategorySuggestion
     * @param Activity|null $activity
     * @param string|null $activitySuggestion
     * @param array|null $devicesIds
     * @param string|null $deviceSuggestion
     * @param VybePeriodListItem $vybePeriodListItem
     * @param int $userCount
     * @param VybeTypeListItem $vybeTypeListItem
     * @param int|null $orderAdvance
     * @param array|null $imagesIds
     * @param array|null $videosIds
     * @param string|null $accessPassword
     * @param VybeAccessListItem $vybeAccessListItem
     * @param VybeShowcaseListItem $vybeShowcaseListItem
     * @param VybeOrderAcceptListItem|null $vybeOrderAcceptListItem
     * @param VybeStatusListItem $vybeStatusListItem
     *
     * @return VybePublishRequest|null
     */
    public function store(
        Vybe $vybe,
        string $title,
        ?Category $category,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?string $subcategorySuggestion,
        ?Activity $activity,
        ?string $activitySuggestion,
        ?array $devicesIds,
        ?string $deviceSuggestion,
        VybePeriodListItem $vybePeriodListItem,
        int $userCount,
        VybeTypeListItem $vybeTypeListItem,
        ?int $orderAdvance,
        ?array $imagesIds,
        ?array $videosIds,
        ?string $accessPassword,
        VybeAccessListItem $vybeAccessListItem,
        VybeShowcaseListItem $vybeShowcaseListItem,
        ?VybeOrderAcceptListItem $vybeOrderAcceptListItem,
        VybeStatusListItem $vybeStatusListItem
    ) : ?VybePublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param Category $category
     *
     * @return VybePublishRequest|null
     */
    public function updateSuggestedCategory(
        VybePublishRequest $vybePublishRequest,
        Category $category
    ) : ?VybePublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param Category $subcategory
     *
     * @return VybePublishRequest|null
     */
    public function updateSuggestedSubcategory(
        VybePublishRequest $vybePublishRequest,
        Category $subcategory
    ) : ?VybePublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param Activity $activity
     *
     * @return VybePublishRequest|null
     */
    public function updateSuggestedActivity(
        VybePublishRequest $vybePublishRequest,
        Activity $activity
    ) : ?VybePublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param Device $device
     *
     * @return VybePublishRequest
     */
    public function updateSuggestedDevice(
        VybePublishRequest $vybePublishRequest,
        Device $device
    ) : VybePublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return VybePublishRequest|null
     */
    public function updateDeviceSuggestion(
        VybePublishRequest $vybePublishRequest,
        DeviceSuggestion $deviceSuggestion
    ) : ?VybePublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param VybeAgeLimitListItem|null $vybeAgeLimitListItem
     *
     * @return VybePublishRequest|null
     */
    public function updateAgeLimit(
        VybePublishRequest $vybePublishRequest,
        ?VybeAgeLimitListItem $vybeAgeLimitListItem
    ) : ?VybePublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param bool $shown
     *
     * @return VybePublishRequest|null
     */
    public function updateShown(
        VybePublishRequest $vybePublishRequest,
        bool $shown
    ) : ?VybePublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param RequestFieldStatusListItem $titleStatus
     * @param RequestFieldStatusListItem $categoryStatus
     * @param RequestFieldStatusListItem|null $subcategoryStatus
     * @param RequestFieldStatusListItem $devicesStatus
     * @param RequestFieldStatusListItem $activityStatus
     * @param RequestFieldStatusListItem $periodStatus
     * @param RequestFieldStatusListItem $userCountStatus
     * @param RequestFieldStatusListItem $schedulesStatus
     * @param RequestFieldStatusListItem|null $orderAdvanceStatus
     * @param RequestFieldStatusListItem $accessStatus
     * @param RequestFieldStatusListItem $showcaseStatus
     * @param RequestFieldStatusListItem $orderAcceptStatus
     * @param RequestFieldStatusListItem $statusStatus
     *
     * @return VybePublishRequest|null
     */
    public function updateStatuses(
        VybePublishRequest $vybePublishRequest,
        RequestFieldStatusListItem $titleStatus,
        RequestFieldStatusListItem $categoryStatus,
        ?RequestFieldStatusListItem $subcategoryStatus,
        RequestFieldStatusListItem $devicesStatus,
        RequestFieldStatusListItem $activityStatus,
        RequestFieldStatusListItem $periodStatus,
        RequestFieldStatusListItem $userCountStatus,
        RequestFieldStatusListItem $schedulesStatus,
        ?RequestFieldStatusListItem $orderAdvanceStatus,
        RequestFieldStatusListItem $accessStatus,
        RequestFieldStatusListItem $showcaseStatus,
        RequestFieldStatusListItem $orderAcceptStatus,
        RequestFieldStatusListItem $statusStatus
    ) : ?VybePublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return VybePublishRequest|null
     */
    public function updateRequestStatus(
        VybePublishRequest $vybePublishRequest,
        RequestStatusListItem $requestStatusListItem
    ) : ?VybePublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param string|null $toastMessageText
     *
     * @return VybePublishRequest|null
     */
    public function updateToastMessageText(
        VybePublishRequest $vybePublishRequest,
        ?string $toastMessageText
    ) : ?VybePublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param LanguageListItem $languageListItem
     *
     * @return VybePublishRequest
     */
    public function updateLanguage(
        VybePublishRequest $vybePublishRequest,
        LanguageListItem $languageListItem
    ) : VybePublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param Admin $admin
     *
     * @return VybePublishRequest
     */
    public function updateAdmin(
        VybePublishRequest $vybePublishRequest,
        Admin $admin
    ) : VybePublishRequest;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return bool
     */
    public function delete(
        VybePublishRequest $vybePublishRequest
    ) : bool;
}
