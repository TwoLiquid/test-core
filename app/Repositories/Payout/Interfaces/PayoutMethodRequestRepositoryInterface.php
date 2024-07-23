<?php

namespace App\Repositories\Payout\Interfaces;

use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Models\MongoDb\Payout\PayoutMethodRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface PayoutMethodRequestRepositoryInterface
 *
 * @package App\Repositories\Payout\Interfaces
 */
interface PayoutMethodRequestRepositoryInterface
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
    ) : ?PayoutMethodRequest;

    /**
     * This method provides getting row
     * with an eloquent model by certain query
     *
     * @param PaymentMethod $paymentMethod
     * @param User $user
     *
     * @return PayoutMethodRequest|null
     */
    public function findPendingForUser(
        PaymentMethod $paymentMethod,
        User $user
    ) : ?PayoutMethodRequest;

    /**
     * This method provides getting row
     * with an eloquent model by certain query
     *
     * @param PaymentMethod $paymentMethod
     * @param User $user
     *
     * @return PayoutMethodRequest|null
     */
    public function findLastForUser(
        PaymentMethod $paymentMethod,
        User $user
    ) : ?PayoutMethodRequest;

    /**
     * This method provides getting row
     * with an eloquent model by certain query
     *
     * @param PaymentMethod $paymentMethod
     *
     * @return PayoutMethodRequest|null
     */
    public function findLast(
        PaymentMethod $paymentMethod,
    ) : ?PayoutMethodRequest;

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
    public function getLastDistinctForUser(
        User $user
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param string|null $id
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $languagesIds
     * @param int|null $payoutMethodId
     * @param array|null $userBalanceTypesIds
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
        ?array $languagesIds,
        ?int $payoutMethodId,
        ?array $userBalanceTypesIds,
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
     * @param array|null $languagesIds
     * @param int|null $payoutMethodId
     * @param array|null $userBalanceTypesIds
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
        ?array $languagesIds,
        ?int $payoutMethodId,
        ?array $userBalanceTypesIds,
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
     * @param PaymentMethod $paymentMethod
     * @param User $user
     *
     * @return PayoutMethodRequest|null
     */
    public function store(
        PaymentMethod $paymentMethod,
        User $user
    ) : ?PayoutMethodRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param bool $shown
     *
     * @return PayoutMethodRequest
     */
    public function updateShown(
        PayoutMethodRequest $payoutMethodRequest,
        bool $shown
    ) : PayoutMethodRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return PayoutMethodRequest
     */
    public function updateRequestStatus(
        PayoutMethodRequest $payoutMethodRequest,
        RequestStatusListItem $requestStatusListItem
    ) : PayoutMethodRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     * @param string|null $text
     *
     * @return PayoutMethodRequest
     */
    public function updateToastMessageType(
        PayoutMethodRequest $payoutMethodRequest,
        ToastMessageTypeListItem $toastMessageTypeListItem,
        ?string $text
    ) : PayoutMethodRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param LanguageListItem $languageListItem
     *
     * @return PayoutMethodRequest
     */
    public function updateLanguage(
        PayoutMethodRequest $payoutMethodRequest,
        LanguageListItem $languageListItem
    ) : PayoutMethodRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param Admin $admin
     *
     * @return PayoutMethodRequest
     */
    public function updateAdmin(
        PayoutMethodRequest $payoutMethodRequest,
        Admin $admin
    ) : PayoutMethodRequest;

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
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return bool
     */
    public function delete(
        PayoutMethodRequest $payoutMethodRequest
    ) : bool;
}
