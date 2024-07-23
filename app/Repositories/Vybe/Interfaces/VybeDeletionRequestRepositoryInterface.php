<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Models\MongoDb\Vybe\Request\Deletion\VybeDeletionRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeDeletionRequestRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeDeletionRequestRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeDeletionRequest|null
     */
    public function findById(
        ?string $id
    ) : ?VybeDeletionRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeDeletionRequest|null
     */
    public function findFullById(
        ?string $id
    ) : ?VybeDeletionRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return VybeDeletionRequest|null
     */
    public function findPendingForVybe(
        Vybe $vybe
    ) : ?VybeDeletionRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return VybeDeletionRequest|null
     */
    public function findLastForVybe(
        Vybe $vybe
    ) : ?VybeDeletionRequest;

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
     * @param string|null $message
     * @param VybeStatusListItem $previousVybeStatusListItem
     *
     * @return VybeDeletionRequest|null
     */
    public function store(
        Vybe $vybe,
        ?string $message,
        VybeStatusListItem $previousVybeStatusListItem
    ) : ?VybeDeletionRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeDeletionRequest $vybeDeletionRequest
     * @param RequestFieldStatusListItem $statusStatus
     *
     * @return VybeDeletionRequest|null
     */
    public function updateStatus(
        VybeDeletionRequest $vybeDeletionRequest,
        RequestFieldStatusListItem $statusStatus
    ) : ?VybeDeletionRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeDeletionRequest $vybeDeletionRequest
     * @param bool $shown
     *
     * @return VybeDeletionRequest
     */
    public function updateShown(
        VybeDeletionRequest $vybeDeletionRequest,
        bool $shown
    ) : VybeDeletionRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeDeletionRequest $vybeDeletionRequest
     * @param string|null $toastMessageText
     *
     * @return VybeDeletionRequest
     */
    public function updateToastMessageText(
        VybeDeletionRequest $vybeDeletionRequest,
        ?string $toastMessageText
    ) : VybeDeletionRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeDeletionRequest $vybeDeletionRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return VybeDeletionRequest|null
     */
    public function updateRequestStatus(
        VybeDeletionRequest $vybeDeletionRequest,
        RequestStatusListItem $requestStatusListItem
    ) : ?VybeDeletionRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeDeletionRequest $vybeDeletionRequest
     * @param LanguageListItem $languageListItem
     *
     * @return VybeDeletionRequest
     */
    public function updateLanguage(
        VybeDeletionRequest $vybeDeletionRequest,
        LanguageListItem $languageListItem
    ) : VybeDeletionRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeDeletionRequest $vybeDeletionRequest
     * @param Admin $admin
     *
     * @return VybeDeletionRequest
     */
    public function updateAdmin(
        VybeDeletionRequest $vybeDeletionRequest,
        Admin $admin
    ) : VybeDeletionRequest;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeDeletionRequest $vybeDeletionRequest
     *
     * @return bool
     */
    public function delete(
        VybeDeletionRequest $vybeDeletionRequest
    ) : bool;
}
