<?php

namespace App\Repositories\Order\Interfaces;

use App\Models\MySql\Order\Order;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface OrderRepositoryInterface
 *
 * @package App\Repositories\Order\Interfaces
 */
interface OrderRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Order|null
     */
    public function findById(
        ?int $id
    ) : ?Order;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Order|null
     */
    public function findFullById(
        ?int $id
    ) : ?Order;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $seller
     * @param User $buyer
     *
     * @return Order|null
     */
    public function findLastBySellerAndBuyer(
        User $seller,
        User $buyer
    ) : ?Order;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param array $ordersIds
     *
     * @return Collection
     */
    public function getOrdersByIds(
        array $ordersIds
    ) : Collection;

    /**
     * This method provides getting all rows
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
     * with an eloquent model with filters
     *
     * @param int|null $orderOverviewId
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
        ?int $orderOverviewId,
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
     * with an eloquent model with pagination and filters
     *
     * @param int|null $orderOverviewId
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
        ?int $orderOverviewId,
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
     * with an eloquent model with filters
     *
     * @param int|null $orderOverviewId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param string|null $seller
     * @param int|null $orderItemId
     *
     * @return Collection
     */
    public function getAllFilteredForAdminLabels(
        ?int $orderOverviewId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?string $seller,
        ?int $orderItemId
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with filters
     *
     * @param User $buyer
     * @param int|null $orderOverviewId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $seller
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllForBuyerFiltered(
        User $buyer,
        ?int $orderOverviewId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $seller,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination and filters
     *
     * @param User $buyer
     * @param int|null $orderOverviewId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $seller
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllForBuyerPaginatedFiltered(
        User $buyer,
        ?int $orderOverviewId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $seller,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with filters
     *
     * @param User $buyer
     * @param int|null $orderOverviewId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $seller
     *
     * @return Collection
     */
    public function getAllForBuyerFilteredForAdminLabels(
        User $buyer,
        ?int $orderOverviewId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $seller
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param User $buyer
     * @param PaymentMethod $paymentMethod
     * @param float|null $amount
     * @param float $amountTax
     * @param float|null $amountTotal
     * @param float $paymentFee
     * @param float $paymentFeeTax
     * @param string $paidAt
     *
     * @return Order|null
     */
    public function store(
        User $buyer,
        PaymentMethod $paymentMethod,
        ?float $amount,
        float $amountTax,
        ?float $amountTotal,
        float $paymentFee,
        float $paymentFeeTax,
        string $paidAt
    ) : ?Order;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Order $order
     * @param User|null $buyer
     * @param PaymentMethod|null $paymentMethod
     * @param float|null $amount
     * @param float|null $amountTax
     * @param float|null $amountTotal
     * @param float|null $paymentFee
     * @param float|null $paymentFeeTax
     * @param string|null $paidAt
     *
     * @return Order
     */
    public function update(
        Order $order,
        ?User $buyer,
        ?PaymentMethod $paymentMethod,
        ?float $amount,
        ?float $amountTax,
        ?float $amountTotal,
        ?float $paymentFee,
        ?float $paymentFeeTax,
        ?string $paidAt
    ) : Order;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Order $order
     * @param float|null $amount
     * @param float|null $amountTax
     * @param float|null $amountTotal
     *
     * @return Order
     */
    public function updateAmount(
        Order $order,
        ?float $amount,
        ?float $amountTax,
        ?float $amountTotal
    ) : Order;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Order $order
     * @param float $amountTotal
     *
     * @return Order
     */
    public function updateAmountTotal(
        Order $order,
        float $amountTotal
    ) : Order;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Order $order
     * @param float $amountTax
     *
     * @return Order
     */
    public function updateAmountTax(
        Order $order,
        float $amountTax
    ) : Order;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Order $order
     * @param float|null $paymentFee
     * @param float|null $paymentFeeTax
     *
     * @return Order
     */
    public function updatePaymentFee(
        Order $order,
        ?float $paymentFee,
        ?float $paymentFeeTax
    ) : Order;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Order $order
     *
     * @return Order
     */
    public function updatePaidAt(
        Order $order
    ) : Order;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Order $order
     *
     * @return bool
     */
    public function delete(
        Order $order
    ) : bool;
}
