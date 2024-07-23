<?php

namespace App\Repositories\Receipt\Interfaces;

use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Models\MongoDb\WithdrawalRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface WithdrawalRequestRepositoryInterface
 *
 * @package App\Repositories\Receipt\Interfaces
 */
interface WithdrawalRequestRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return WithdrawalRequest|null
     */
    public function findById(
        ?string $id
    ) : ?WithdrawalRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return WithdrawalRequest|null
     */
    public function findPendingForUser(
        User $user
    ) : ?WithdrawalRequest;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return WithdrawalRequest|null
     */
    public function findLastForUser(
        User $user
    ) : ?WithdrawalRequest;

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
     * with an eloquent model
     *
     * @param string|null $requestId
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param array|null $languagesIds
     * @param int|null $payoutMethodId
     * @param int|null $amount
     * @param array|null $userBalanceTypesIds
     * @param array|null $requestStatusesIds
     * @param array|null $receiptStatusesIds
     * @param string|null $admin
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFiltered(
        ?string $requestId,
        ?int $userId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?array $languagesIds,
        ?int $payoutMethodId,
        ?int $amount,
        ?array $userBalanceTypesIds,
        ?array $requestStatusesIds,
        ?array $receiptStatusesIds,
        ?string $admin,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param string|null $requestId
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param array|null $languagesIds
     * @param int|null $payoutMethodId
     * @param int|null $amount
     * @param array|null $userBalanceTypesIds
     * @param array|null $requestStatusesIds
     * @param array|null $receiptStatusesIds
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
        ?int $userId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?array $languagesIds,
        ?int $payoutMethodId,
        ?int $amount,
        ?array $userBalanceTypesIds,
        ?array $requestStatusesIds,
        ?array $receiptStatusesIds,
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
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getPendingForUser(
        User $user
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getPendingAndDeclinedForUser(
        User $user
    ) : Collection;

    /**
     * This method provides creating row
     * with an eloquent model
     *
     * @param User $user
     * @param PaymentMethod $payoutMethod
     * @param int $amount
     *
     * @return WithdrawalRequest|null
     */
    public function store(
        User $user,
        PaymentMethod $payoutMethod,
        int $amount
    ) : ?WithdrawalRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param WithdrawalRequest $withdrawalRequest
     * @param User|null $user
     * @param PaymentMethod|null $payoutMethod
     * @param int|null $amount
     *
     * @return WithdrawalRequest|null
     */
    public function update(
        WithdrawalRequest $withdrawalRequest,
        ?User $user,
        ?PaymentMethod $payoutMethod,
        ?int $amount
    ) : ?WithdrawalRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param WithdrawalRequest $withdrawalRequest
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return WithdrawalRequest
     */
    public function updateReceipt(
        WithdrawalRequest $withdrawalRequest,
        WithdrawalReceipt $withdrawalReceipt
    ) : WithdrawalRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param WithdrawalRequest $withdrawalRequest
     * @param bool $shown
     *
     * @return WithdrawalRequest
     */
    public function updateShown(
        WithdrawalRequest $withdrawalRequest,
        bool $shown
    ) : WithdrawalRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param WithdrawalRequest $withdrawalRequest
     * @param RequestStatusListItem $requestStatusListItem
     * @param string|null $toastMessageText
     *
     * @return WithdrawalRequest|null
     */
    public function updateRequestStatus(
        WithdrawalRequest $withdrawalRequest,
        RequestStatusListItem $requestStatusListItem,
        ?string $toastMessageText = null
    ) : ?WithdrawalRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param WithdrawalRequest $withdrawalRequest
     * @param Admin $admin
     *
     * @return WithdrawalRequest
     */
    public function updateAdmin(
        WithdrawalRequest $withdrawalRequest,
        Admin $admin
    ) : WithdrawalRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param WithdrawalRequest $withdrawalRequest
     * @param LanguageListItem $languageListItem
     *
     * @return WithdrawalRequest
     */
    public function updateLanguage(
        WithdrawalRequest $withdrawalRequest,
        LanguageListItem $languageListItem
    ) : WithdrawalRequest;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return bool
     */
    public function delete(
        WithdrawalRequest $withdrawalRequest
    ) : bool;
}
