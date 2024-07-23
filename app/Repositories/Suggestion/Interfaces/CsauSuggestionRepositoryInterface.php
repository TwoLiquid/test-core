<?php

namespace App\Repositories\Suggestion\Interfaces;

use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Category;
use App\Models\MySql\Unit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface CsauSuggestionRepositoryInterface
 *
 * @package App\Repositories\Suggestion\Interfaces
 */
interface CsauSuggestionRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return CsauSuggestion|null
     */
    public function findById(
        ?string $id
    ) : ?CsauSuggestion;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return CsauSuggestion|null
     */
    public function findFirstForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : ?CsauSuggestion;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return CsauSuggestion|null
     */
    public function findFirstForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : ?CsauSuggestion;

    /**
     * This method provides getting data
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllCount() : int;

    /**
     * This method provides getting data
     * with an eloquent model by certain query
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection
     */
    public function getAllForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : Collection;

    /**
     * This method provides getting data
     * with an eloquent model by certain query
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection
     */
    public function getAllForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : Collection;

    /**
     * This method provides getting data
     * with an eloquent model by certain query
     *
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $vybeVersion
     * @param string|null $vybeTitle
     * @param array|null $categoriesIds
     * @param array|null $subcategoriesIds
     * @param array|null $activitiesIds
     * @param array|null $unitTypesIds
     * @param array|null $unitsIds
     *
     * @return Collection
     */
    public function getAllPending(
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $vybeVersion,
        ?string $vybeTitle,
        ?array $categoriesIds,
        ?array $subcategoriesIds,
        ?array $activitiesIds,
        ?array $unitTypesIds,
        ?array $unitsIds
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $vybeVersion
     * @param string|null $vybeTitle
     * @param array|null $categoriesIds
     * @param array|null $subcategoriesIds
     * @param array|null $activitiesIds
     * @param array|null $unitTypesIds
     * @param array|null $unitsIds
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPendingPaginated(
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $vybeVersion,
        ?string $vybeTitle,
        ?array $categoriesIds,
        ?array $subcategoriesIds,
        ?array $activitiesIds,
        ?array $unitTypesIds,
        ?array $unitsIds,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param VybePublishRequest|null $vybePublishRequest
     * @param VybeChangeRequest|null $vybeChangeRequest
     * @param Category|null $category
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param string|null $subcategorySuggestion
     * @param Activity|null $activity
     * @param string|null $activitySuggestion
     * @param Unit|null $unit
     * @param string|null $unitSuggestion
     *
     * @return CsauSuggestion|null
     */
    public function store(
        ?VybePublishRequest $vybePublishRequest,
        ?VybeChangeRequest $vybeChangeRequest,
        ?Category $category,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?string $subcategorySuggestion,
        ?Activity $activity,
        ?string $activitySuggestion,
        ?Unit $unit,
        ?string $unitSuggestion
    ) : ?CsauSuggestion;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param CsauSuggestion $csauSuggestion
     * @param Category $category
     *
     * @return CsauSuggestion
     */
    public function updateCategory(
        CsauSuggestion $csauSuggestion,
        Category $category
    ) : CsauSuggestion;

    /**
     * This method provides updating rows
     * with an eloquent model by certain query
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param Category $category
     */
    public function acceptCategoryForVybePublishRequest(
        VybePublishRequest $vybePublishRequest,
        Category $category
    ) : void;

    /**
     * This method provides updating rows
     * with an eloquent model by certain query
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Category $category
     */
    public function acceptCategoryForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest,
        Category $category
    ) : void;

    /**
     * This method provides updating rows
     * with an eloquent model by certain query
     *
     * @param VybePublishRequest $vybePublishRequest
     */
    public function declineCategoryForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : void;

    /**
     * This method provides updating rows
     * with an eloquent model by certain query
     *
     * @param VybeChangeRequest $vybeChangeRequest
     */
    public function declineCategoryForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : void;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param CsauSuggestion $csauSuggestion
     * @param RequestFieldStatusListItem $requestFieldStatusListItem
     *
     * @return CsauSuggestion
     */
    public function updateCategoryStatus(
        CsauSuggestion $csauSuggestion,
        RequestFieldStatusListItem $requestFieldStatusListItem
    ) : CsauSuggestion;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param CsauSuggestion $csauSuggestion
     * @param Category $subcategory
     *
     * @return CsauSuggestion
     */
    public function updateSubcategory(
        CsauSuggestion $csauSuggestion,
        Category $subcategory
    ) : CsauSuggestion;

    /**
     * This method provides updating rows
     * with an eloquent model by certain query
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param Category $subcategory
     */
    public function acceptSubcategoryForVybePublishRequest(
        VybePublishRequest $vybePublishRequest,
        Category $subcategory
    ) : void;

    /**
     * This method provides updating rows
     * with an eloquent model by certain query
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Category $subcategory
     */
    public function acceptSubcategoryForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest,
        Category $subcategory
    ) : void;

    /**
     * This method provides updating rows
     * with an eloquent model by certain query
     *
     * @param VybePublishRequest $vybePublishRequest
     */
    public function declineSubcategoryForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : void;

    /**
     * This method provides updating rows
     * with an eloquent model by certain query
     *
     * @param VybeChangeRequest $vybeChangeRequest
     */
    public function declineSubcategoryForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : void;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param CsauSuggestion $csauSuggestion
     * @param RequestFieldStatusListItem $requestFieldStatusListItem
     *
     * @return CsauSuggestion
     */
    public function updateSubcategoryStatus(
        CsauSuggestion $csauSuggestion,
        RequestFieldStatusListItem $requestFieldStatusListItem
    ) : CsauSuggestion;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param CsauSuggestion $csauSuggestion
     * @param Activity $activity
     *
     * @return CsauSuggestion
     */
    public function updateActivity(
        CsauSuggestion $csauSuggestion,
        Activity $activity
    ) : CsauSuggestion;

    /**
     * This method provides updating rows
     * with an eloquent model by certain query
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param Activity $activity
     */
    public function acceptActivityForVybePublishRequest(
        VybePublishRequest $vybePublishRequest,
        Activity $activity
    ) : void;

    /**
     * This method provides updating rows
     * with an eloquent model by certain query
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Activity $activity
     */
    public function acceptActivityForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest,
        Activity $activity
    ) : void;

    /**
     * This method provides updating rows
     * with an eloquent model by certain query
     *
     * @param VybePublishRequest $vybePublishRequest
     */
    public function declineActivityForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : void;

    /**
     * This method provides updating rows
     * with an eloquent model by certain query
     *
     * @param VybeChangeRequest $vybeChangeRequest
     */
    public function declineActivityForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : void;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param CsauSuggestion $csauSuggestion
     * @param RequestFieldStatusListItem $requestFieldStatusListItem
     *
     * @return CsauSuggestion
     */
    public function updateActivityStatus(
        CsauSuggestion $csauSuggestion,
        RequestFieldStatusListItem $requestFieldStatusListItem
    ) : CsauSuggestion;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param CsauSuggestion $csauSuggestion
     * @param Unit $unit
     *
     * @return CsauSuggestion
     */
    public function updateUnit(
        CsauSuggestion $csauSuggestion,
        Unit $unit
    ) : CsauSuggestion;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param CsauSuggestion $csauSuggestion
     * @param RequestFieldStatusListItem $requestFieldStatusListItem
     *
     * @return CsauSuggestion
     */
    public function updateUnitStatus(
        CsauSuggestion $csauSuggestion,
        RequestFieldStatusListItem $requestFieldStatusListItem
    ) : CsauSuggestion;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param CsauSuggestion $csauSuggestion
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return CsauSuggestion
     */
    public function updateStatus(
        CsauSuggestion $csauSuggestion,
        RequestStatusListItem $requestStatusListItem
    ) : CsauSuggestion;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param CsauSuggestion $csauSuggestion
     * @param Admin $admin
     *
     * @return CsauSuggestion
     */
    public function updateAdmin(
        CsauSuggestion $csauSuggestion,
        Admin $admin
    ) : CsauSuggestion;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param CsauSuggestion $csauSuggestion
     *
     * @return bool
     */
    public function delete(
        CsauSuggestion $csauSuggestion
    ) : bool;

    /**
     * This method provides deleting existing rows
     * with an eloquent model by certain query
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return bool
     */
    public function deletePendingForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : bool;

    /**
     * This method provides deleting existing rows
     * with an eloquent model by certain query
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return bool
     */
    public function deletePendingForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : bool;
}
