<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Models\MongoDb\Vybe\Request\Unpublish\VybeUnpublishRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeUnpublishRequestRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeUnpublishRequestRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeUnpublishRequest|null
     */
    public function findById(
        ?string $id
    ) : ?VybeUnpublishRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeUnpublishRequest|null
     */
    public function findFullById(
        ?string $id
    ) : ?VybeUnpublishRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return VybeUnpublishRequest|null
     */
    public function findPendingForVybe(
        Vybe $vybe
    ) : ?VybeUnpublishRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     *
     * @return VybeUnpublishRequest|null
     */
    public function findLastForVybe(
        Vybe $vybe
    ) : ?VybeUnpublishRequest;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllCount() : int;

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
     * @return VybeUnpublishRequest|null
     */
    public function store(
        Vybe $vybe,
        ?string $message,
        VybeStatusListItem $previousVybeStatusListItem
    ) : ?VybeUnpublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     * @param RequestFieldStatusListItem $statusStatus
     *
     * @return VybeUnpublishRequest|null
     */
    public function updateStatus(
        VybeUnpublishRequest $vybeUnpublishRequest,
        RequestFieldStatusListItem $statusStatus
    ) : ?VybeUnpublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     * @param bool $shown
     *
     * @return VybeUnpublishRequest
     */
    public function updateShown(
        VybeUnpublishRequest $vybeUnpublishRequest,
        bool $shown
    ) : VybeUnpublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return VybeUnpublishRequest|null
     */
    public function updateRequestStatus(
        VybeUnpublishRequest $vybeUnpublishRequest,
        RequestStatusListItem $requestStatusListItem
    ) : ?VybeUnpublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     * @param string|null $toastMessageText
     *
     * @return VybeUnpublishRequest|null
     */
    public function updateToastMessageText(
        VybeUnpublishRequest $vybeUnpublishRequest,
        ?string $toastMessageText
    ) : ?VybeUnpublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     * @param LanguageListItem $languageListItem
     *
     * @return VybeUnpublishRequest
     */
    public function updateLanguage(
        VybeUnpublishRequest $vybeUnpublishRequest,
        LanguageListItem $languageListItem
    ) : VybeUnpublishRequest;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     * @param Admin $admin
     *
     * @return VybeUnpublishRequest
     */
    public function updateAdmin(
        VybeUnpublishRequest $vybeUnpublishRequest,
        Admin $admin
    ) : VybeUnpublishRequest;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return bool
     */
    public function delete(
        VybeUnpublishRequest $vybeUnpublishRequest
    ) : bool;
}
