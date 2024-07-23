<?php

namespace App\Repositories\Sale\Interfaces;

use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Sale;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface SaleRepositoryInterface
 *
 * @package App\Repositories\Sale\Interfaces
 */
interface SaleRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Sale|null
     */
    public function findById(
        ?int $id
    ) : ?Sale;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param int|null $id
     *
     * @return Sale|null
     */
    public function findFullById(
        ?int $id
    ) : ?Sale;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllCount() : int;

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
     * @param string|null $seller
     * @param int|null $orderItemId
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFiltered(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?string $seller,
        ?int $orderItemId,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
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
     * @param string|null $seller
     * @param int|null $orderItemId
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFiltered(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?string $seller,
        ?int $orderItemId,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
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
     * @param string|null $seller
     * @param int|null $orderItemId
     *
     * @return Collection
     */
    public function getAllFilteredForAdminLabels(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?string $seller,
        ?int $orderItemId
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param int|null $orderItemId
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFilteredForUser(
        User $user,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?int $orderItemId,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
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
     * @param string|null $buyer
     * @param int|null $orderItemId
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
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
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?int $orderItemId,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
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
     * @param string|null $buyer
     * @param int|null $orderItemId
     *
     * @return Collection
     */
    public function getAllFilteredForUserForAdminLabels(
        User $user,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?int $orderItemId
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Order $order
     * @param User $seller
     * @param float $amountEarned
     * @param float $amountTotal
     * @param float $handlingFee
     *
     * @return Sale|null
     */
    public function store(
        Order $order,
        User $seller,
        float $amountEarned,
        float $amountTotal,
        float $handlingFee
    ) : ?Sale;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Sale $sale
     * @param Order|null $order
     * @param User|null $seller
     * @param float|null $amountEarned
     * @param float|null $amountTotal
     * @param float|null $handlingFee
     *
     * @return Sale
     */
    public function update(
        Sale $sale,
        ?Order $order,
        ?User $seller,
        ?float $amountEarned,
        ?float $amountTotal,
        ?float $handlingFee
    ) : Sale;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param Sale $sale
     * @param OrderItem $orderItem
     */
    public function attachOrderItem(
        Sale $sale,
        OrderItem $orderItem
    ) : void;

    /**
     * This method provides attaching existing rows
     * with a current model with belongs to many relations
     *
     * @param Sale $sale
     * @param array $orderItemsIds
     * @param bool|null $detaching
     */
    public function attachOrderItems(
        Sale $sale,
        array $orderItemsIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing row
     * with a current model with belongs to many relations
     *
     * @param Sale $sale
     * @param OrderItem $orderItem
     */
    public function detachOrderItem(
        Sale $sale,
        OrderItem $orderItem
    ) : void;

    /**
     * This method provides detaching existing rows
     * with a current model with belongs to many relations
     *
     * @param Sale $sale
     * @param array $orderItemsIds
     */
    public function detachOrderItems(
        Sale $sale,
        array $orderItemsIds
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Sale $sale
     *
     * @return bool
     */
    public function delete(
        Sale $sale
    ) : bool;
}
