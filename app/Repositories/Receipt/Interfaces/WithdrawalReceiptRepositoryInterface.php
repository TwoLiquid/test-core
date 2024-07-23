<?php

namespace App\Repositories\Receipt\Interfaces;

use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusListItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface WithdrawalReceiptRepositoryInterface
 *
 * @package App\Repositories\Receipt\Interfaces
 */
interface WithdrawalReceiptRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary keys
     *
     * @param int|null $id
     *
     * @return WithdrawalReceipt|null
     */
    public function findById(
        ?int $id
    ) : ?WithdrawalReceipt;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param int|null $id
     *
     * @return WithdrawalReceipt|null
     */
    public function findFullById(
        ?int $id
    ) : ?WithdrawalReceipt;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllCount() : int;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
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
     * @param int|null $id
     * @param string|null $requestId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $client
     * @param int|null $payoutMethodId
     * @param array|null $withdrawReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFiltered(
        ?int $id,
        ?string $requestId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $client,
        ?int $payoutMethodId,
        ?array $withdrawReceiptStatusesIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
     *
     * @param int|null $id
     * @param string|null $requestId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $client
     * @param int|null $payoutMethodId
     * @param array|null $withdrawReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFiltered(
        ?int $id,
        ?string $requestId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $client,
        ?int $payoutMethodId,
        ?array $withdrawReceiptStatusesIds,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param int|null $id
     * @param string|null $requestId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $client
     * @param int|null $payoutMethodId
     *
     * @return Collection
     */
    public function getAllFilteredForAdminLabels(
        ?int $id,
        ?string $requestId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $client,
        ?int $payoutMethodId
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param int|null $id
     * @param string|null $requestId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $payoutMethodId
     * @param array|null $withdrawReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFilteredForUser(
        User $user,
        ?int $id,
        ?string $requestId,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $payoutMethodId,
        ?array $withdrawReceiptStatusesIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
     *
     * @param User $user
     * @param int|null $id
     * @param string|null $requestId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $payoutMethodId
     * @param array|null $withdrawReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFilteredForUser(
        User $user,
        ?int $id,
        ?string $requestId,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $payoutMethodId,
        ?array $withdrawReceiptStatusesIds,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param int|null $id
     * @param string|null $requestId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $payoutMethodId
     *
     * @return Collection
     */
    public function getAllFilteredForUserForAdminLabels(
        User $user,
        ?int $id,
        ?string $requestId,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $payoutMethodId
    ) : Collection;

    /**
     * This method provides getting rows numbers
     * with an eloquent model by certain query
     *
     * @param array $withdrawalReceiptsIds
     *
     * @return WithdrawalReceipt
     */
    public function getStatusesByIdsCount(
        array $withdrawalReceiptsIds
    ) : WithdrawalReceipt;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param int|null $receiptId
     * @param string|null $requestId
     * @param string|null $requestDateFrom
     * @param string|null $requestDateTo
     * @param string|null $receiptDateFrom
     * @param string|null $receiptDateTo
     * @param int|null $amount
     * @param array|null $payoutMethodsIds
     * @param array|null $withdrawReceiptStatusesIds
     * @param array|null $requestStatusesIds
     *
     * @return Collection
     */
    public function getForDashboardFiltered(
        User $user,
        ?int $receiptId,
        ?string $requestId,
        ?string $requestDateFrom,
        ?string $requestDateTo,
        ?string $receiptDateFrom,
        ?string $receiptDateTo,
        ?int $amount,
        ?array $payoutMethodsIds,
        ?array $withdrawReceiptStatusesIds,
        ?array $requestStatusesIds
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
     *
     * @param User $user
     * @param int|null $receiptId
     * @param string|null $requestId
     * @param string|null $requestDateFrom
     * @param string|null $requestDateTo
     * @param string|null $receiptDateFrom
     * @param string|null $receiptDateTo
     * @param int|null $amount
     * @param array|null $payoutMethodsIds
     * @param array|null $withdrawReceiptStatusesIds
     * @param array|null $requestStatusesIds
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getForDashboardFilteredPaginated(
        User $user,
        ?int $receiptId,
        ?string $requestId,
        ?string $requestDateFrom,
        ?string $requestDateTo,
        ?string $receiptDateFrom,
        ?string $receiptDateTo,
        ?int $amount,
        ?array $payoutMethodsIds,
        ?array $withdrawReceiptStatusesIds,
        ?array $requestStatusesIds,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param User $user
     * @param PaymentMethod $payoutMethod
     * @param WithdrawalReceiptStatusListItem $withdrawalReceiptStatusListItem
     * @param string|null $description
     * @param float $amount
     * @param float|null $amountTotal
     * @param float|null $paymentFee
     *
     * @return WithdrawalReceipt|null
     */
    public function store(
        User $user,
        PaymentMethod $payoutMethod,
        WithdrawalReceiptStatusListItem $withdrawalReceiptStatusListItem,
        ?string $description,
        float $amount,
        ?float $amountTotal,
        ?float $paymentFee
    ) : ?WithdrawalReceipt;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param User|null $user
     * @param PaymentMethod|null $payoutMethod
     * @param WithdrawalReceiptStatusListItem|null $withdrawalReceiptStatusListItem
     * @param string|null $description
     * @param float|null $amount
     * @param float|null $amountTotal
     * @param float|null $paymentFee
     *
     * @return WithdrawalReceipt
     */
    public function update(
        WithdrawalReceipt $withdrawalReceipt,
        ?User $user,
        ?PaymentMethod $payoutMethod,
        ?WithdrawalReceiptStatusListItem $withdrawalReceiptStatusListItem,
        ?string $description,
        ?float $amount,
        ?float $amountTotal,
        ?float $paymentFee
    ) : WithdrawalReceipt;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param float $paymentFee
     * @param float $amountTotal
     *
     * @return WithdrawalReceipt
     */
    public function updateTotalAmountAndPaymentFee(
        WithdrawalReceipt $withdrawalReceipt,
        float $paymentFee,
        float $amountTotal
    ) : WithdrawalReceipt;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param WithdrawalReceiptStatusListItem $withdrawalReceiptStatusListItem
     *
     * @return WithdrawalReceipt
     */
    public function updateStatus(
        WithdrawalReceipt $withdrawalReceipt,
        WithdrawalReceiptStatusListItem $withdrawalReceiptStatusListItem
    ) : WithdrawalReceipt;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return bool
     */
    public function delete(
        WithdrawalReceipt $withdrawalReceipt
    ) : bool;
}
