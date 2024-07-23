<?php

namespace App\Repositories\Billing\Interfaces;

use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Models\MongoDb\User\Billing\BillingChangeRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\Place\RegionPlace;
use App\Models\MySql\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface BillingChangeRequestRequestRepositoryInterface
 *
 * @package App\Repositories\Billing\Interfaces
 */
interface BillingChangeRequestRequestRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return Model|null
     */
    public function findById(
        ?string $id
    ) : ?Model;

    /**
     * This method provides getting row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return BillingChangeRequest|null
     */
    public function findPendingForUser(
        User $user
    ) : ?BillingChangeRequest;

    /**
     * This method provides getting row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return BillingChangeRequest|null
     */
    public function findLastForUser(
        User $user
    ) : ?BillingChangeRequest;

    /**
     * This method provides getting row
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param bool $shown
     *
     * @return BillingChangeRequest|null
     */
    public function findLastShownForUser(
        User $user,
        bool $shown
    ) : ?BillingChangeRequest;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllCount() : int;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllBuyersCount() : int;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllSellersCount() : int;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllAffiliatesCount() : int;

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
        ?int $page = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param string|null $id
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param string|null $oldCountry
     * @param string|null $newCountry
     * @param array|null $languagesIds
     * @param array|null $userBalanceTypesIds
     * @param array|null $userStatusesIds
     * @param array|null $requestStatusesIds
     * @param string|null $admin
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFiltered(
        ?string $id,
        ?int $userId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?string $oldCountry,
        ?string $newCountry,
        ?array $languagesIds,
        ?array $userBalanceTypesIds,
        ?array $userStatusesIds,
        ?array $requestStatusesIds,
        ?string $admin,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string|null $id
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param string|null $oldCountry
     * @param string|null $newCountry
     * @param array|null $languagesIds
     * @param array|null $userBalanceTypesIds
     * @param array|null $userStatusesIds
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
        ?string $id,
        ?int $userId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?string $oldCountry,
        ?string $newCountry,
        ?array $languagesIds,
        ?array $userBalanceTypesIds,
        ?array $userStatusesIds,
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
     * This method provides checking row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return bool
     */
    public function existsPendingForUser(
        User $user
    ) : bool;

    /**
     * This method provides creating row
     * with an eloquent model
     *
     * @param User $user
     * @param CountryPlace $countryPlace
     * @param CountryPlace $previousCountryPlace
     * @param RegionPlace|null $regionPlace
     *
     * @return BillingChangeRequest|null
     */
    public function store(
        User $user,
        CountryPlace $countryPlace,
        CountryPlace $previousCountryPlace,
        ?RegionPlace $regionPlace
    ) : ?BillingChangeRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param BillingChangeRequest $billingChangeRequest
     * @param RequestFieldStatusListItem $countryPlaceStatus
     * @param string|null $toastMessageText
     *
     * @return BillingChangeRequest|null
     */
    public function updateStatuses(
        BillingChangeRequest $billingChangeRequest,
        RequestFieldStatusListItem $countryPlaceStatus,
        ?string $toastMessageText
    ) : ?BillingChangeRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param BillingChangeRequest $billingChangeRequest
     * @param bool $shown
     *
     * @return BillingChangeRequest
     */
    public function updateShown(
        BillingChangeRequest $billingChangeRequest,
        bool $shown
    ) : BillingChangeRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param BillingChangeRequest $billingChangeRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return BillingChangeRequest
     */
    public function updateRequestStatus(
        BillingChangeRequest $billingChangeRequest,
        RequestStatusListItem $requestStatusListItem
    ) : BillingChangeRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param BillingChangeRequest $billingChangeRequest
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     *
     * @return BillingChangeRequest
     */
    public function updateToastMessageType(
        BillingChangeRequest $billingChangeRequest,
        ToastMessageTypeListItem $toastMessageTypeListItem
    ) : BillingChangeRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param BillingChangeRequest $billingChangeRequest
     * @param LanguageListItem $languageListItem
     *
     * @return BillingChangeRequest
     */
    public function updateLanguage(
        BillingChangeRequest $billingChangeRequest,
        LanguageListItem $languageListItem
    ) : BillingChangeRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param BillingChangeRequest $billingChangeRequest
     * @param Admin $admin
     *
     * @return BillingChangeRequest
     */
    public function updateAdmin(
        BillingChangeRequest $billingChangeRequest,
        Admin $admin
    ) : BillingChangeRequest;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return bool
     */
    public function deleteForUser(
        User $user
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param BillingChangeRequest $billingChangeRequest
     *
     * @return bool
     */
    public function delete(
        BillingChangeRequest $billingChangeRequest
    ) : bool;
}
