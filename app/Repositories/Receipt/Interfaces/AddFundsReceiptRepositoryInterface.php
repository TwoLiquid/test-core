<?php

namespace App\Repositories\Receipt\Interfaces;

use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusListItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface AddFundsReceiptRepositoryInterface
 *
 * @package App\Repositories\Receipt\Interfaces
 */
interface AddFundsReceiptRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary keys
     *
     * @param int|null $id
     *
     * @return AddFundsReceipt|null
     */
    public function findById(
        ?int $id
    ) : ?AddFundsReceipt;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param int|null $id
     *
     * @return AddFundsReceipt|null
     */
    public function findFullById(
        ?int $id
    ) : ?AddFundsReceipt;

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
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param int|null $paymentMethodId
     * @param array|null $addFundsReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getFiltered(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?int $paymentMethodId,
        ?array $addFundsReceiptStatusesIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
     *
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param int|null $paymentMethodId
     * @param array|null $addFundsReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getPaginatedFiltered(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?int $paymentMethodId,
        ?array $addFundsReceiptStatusesIds,
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
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param int|null $paymentMethodId
     *
     * @return Collection
     */
    public function getFilteredForAdminLabels(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?int $paymentMethodId
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $paymentMethodId
     * @param array|null $addFundsReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getFilteredForUser(
        User $user,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $paymentMethodId,
        ?array $addFundsReceiptStatusesIds,
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
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $paymentMethodId
     * @param array|null $addFundsReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getPaginatedFilteredForUser(
        User $user,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $paymentMethodId,
        ?array $addFundsReceiptStatusesIds,
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
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $paymentMethodId
     *
     * @return Collection
     */
    public function getFilteredForUserForAdminLabels(
        User $user,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $paymentMethodId
    ) : Collection;

    /**
     * This method provides getting rows numbers
     * with an eloquent model by certain query
     *
     * @param array $addFundsReceiptsIds
     *
     * @return AddFundsReceipt
     */
    public function getStatusesByIdsCount(
        array $addFundsReceiptsIds
    ) : AddFundsReceipt;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param int|null $receiptId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $amount
     * @param int|null $paymentFee
     * @param int|null $total
     * @param array|null $paymentMethodsIds
     * @param array|null $addFundsReceiptStatusesIds
     *
     * @return Collection
     */
    public function getForDashboardFiltered(
        User $user,
        ?int $receiptId,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $amount,
        ?int $paymentFee,
        ?int $total,
        ?array $paymentMethodsIds,
        ?array $addFundsReceiptStatusesIds
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
     *
     * @param User $user
     * @param int|null $receiptId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $amount
     * @param int|null $paymentFee
     * @param int|null $total
     * @param array|null $paymentMethodsIds
     * @param array|null $addFundsReceiptStatusesIds
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getForDashboardFilteredPaginated(
        User $user,
        ?int $receiptId,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $amount,
        ?int $paymentFee,
        ?int $total,
        ?array $paymentMethodsIds,
        ?array $addFundsReceiptStatusesIds,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param User $user
     * @param PaymentMethod $paymentMethod
     * @param AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem
     * @param string|null $description
     * @param float $amount
     * @param float|null $amountTotal
     * @param float|null $paymentFee
     *
     * @return AddFundsReceipt|null
     */
    public function store(
        User $user,
        PaymentMethod $paymentMethod,
        AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem,
        ?string $description,
        float $amount,
        ?float $amountTotal,
        ?float $paymentFee
    ) : ?AddFundsReceipt;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param AddFundsReceipt $addFundsReceipt
     * @param User|null $user
     * @param PaymentMethod|null $paymentMethod
     * @param AddFundsReceiptStatusListItem|null $addFundsReceiptStatusListItem
     * @param string|null $description
     * @param float|null $amount
     * @param float|null $amountTotal
     * @param float|null $paymentFee
     *
     * @return AddFundsReceipt
     */
    public function update(
        AddFundsReceipt $addFundsReceipt,
        ?User $user,
        ?PaymentMethod $paymentMethod,
        ?AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem,
        ?string $description,
        ?float $amount,
        ?float $amountTotal,
        ?float $paymentFee
    ) : AddFundsReceipt;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param AddFundsReceipt $addFundsReceipt
     * @param float $paymentFee
     * @param float $amountTotal
     * @param string|null $description
     * @param AddFundsReceiptStatusListItem|null $addFundsReceiptStatusListItem
     *
     * @return AddFundsReceipt
     */
    public function updatePaymentFields(
        AddFundsReceipt $addFundsReceipt,
        float $paymentFee,
        float $amountTotal,
        ?string $description,
        ?AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem
    ) : AddFundsReceipt;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param AddFundsReceipt $addFundsReceipt
     * @param AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem
     *
     * @return AddFundsReceipt
     */
    public function updateStatus(
        AddFundsReceipt $addFundsReceipt,
        AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem
    ) : AddFundsReceipt;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return bool
     */
    public function delete(
        AddFundsReceipt $addFundsReceipt
    ) : bool;
}
