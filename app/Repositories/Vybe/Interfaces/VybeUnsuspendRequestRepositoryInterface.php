<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Models\MongoDb\Vybe\Request\Unsuspend\VybeUnsuspendRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeUnsuspendRequestRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeUnsuspendRequestRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeUnsuspendRequest|null
     */
    public function findById(
        ?string $id
    ) : ?VybeUnsuspendRequest;

    /**
     * This method provides finding a single row
     *  with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeUnsuspendRequest|null
     */
    public function findFullById(
        ?string $id
    ) : ?VybeUnsuspendRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return VybeUnsuspendRequest|null
     */
    public function findPendingForVybe(
        Vybe $vybe
    ) : ?VybeUnsuspendRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return VybeUnsuspendRequest|null
     */
    public function findLastForVybe(
        Vybe $vybe
    ) : ?VybeUnsuspendRequest;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllCount() : int;

    /**
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
     * @return VybeUnsuspendRequest|null
     */
    public function store(
        Vybe $vybe,
        ?string $message,
        VybeStatusListItem $previousVybeStatusListItem
    ) : ?VybeUnsuspendRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     * @param RequestFieldStatusListItem $statusStatus
     *
     * @return VybeUnsuspendRequest|null
     */
    public function updateStatus(
        VybeUnsuspendRequest $vybeUnsuspendRequest,
        RequestFieldStatusListItem $statusStatus
    ) : ?VybeUnsuspendRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     * @param bool $shown
     *
     * @return VybeUnsuspendRequest
     */
    public function updateShown(
        VybeUnsuspendRequest $vybeUnsuspendRequest,
        bool $shown
    ) : VybeUnsuspendRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     * @param string|null $toastMessageText
     *
     * @return VybeUnsuspendRequest
     */
    public function updateToastMessageText(
        VybeUnsuspendRequest $vybeUnsuspendRequest,
        ?string $toastMessageText
    ) : VybeUnsuspendRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return VybeUnsuspendRequest|null
     */
    public function updateRequestStatus(
        VybeUnsuspendRequest $vybeUnsuspendRequest,
        RequestStatusListItem $requestStatusListItem
    ) : ?VybeUnsuspendRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     * @param LanguageListItem $languageListItem
     *
     * @return VybeUnsuspendRequest
     */
    public function updateLanguage(
        VybeUnsuspendRequest $vybeUnsuspendRequest,
        LanguageListItem $languageListItem
    ) : VybeUnsuspendRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     * @param Admin $admin
     *
     * @return VybeUnsuspendRequest
     */
    public function updateAdmin(
        VybeUnsuspendRequest $vybeUnsuspendRequest,
        Admin $admin
    ) : VybeUnsuspendRequest;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeUnsuspendRequest $vybeUnsuspendRequest
     *
     * @return bool
     */
    public function delete(
        VybeUnsuspendRequest $vybeUnsuspendRequest
    ) : bool;
}
