<?php

namespace App\Repositories\Tip\Interfaces;

use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Tip\Tip;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface TipRepositoryInterface
 *
 * @package App\Repositories\Tip\Interfaces
 */
interface TipRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Tip|null
     */
    public function findById(
        ?int $id
    ) : ?Tip;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Tip|null
     */
    public function findFullById(
        ?int $id
    ) : ?Tip;

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
     * @param int|null $orderItemId
     * @param array|null $vybeTypesIds
     * @param string|null $buyer
     * @param string|null $seller
     * @param array|null $paymentMethodsIds
     * @param array|null $orderItemStatusesIds
     * @param int|null $tipInvoiceBuyerId
     * @param array|null $tipInvoiceBuyerStatusesIds
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $tipInvoiceSellerId
     * @param array|null $tipInvoiceSellerStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFiltered(
        ?int $orderItemId,
        ?array $vybeTypesIds,
        ?string $buyer,
        ?string $seller,
        ?array $paymentMethodsIds,
        ?array $orderItemStatusesIds,
        ?int $tipInvoiceBuyerId,
        ?array $tipInvoiceBuyerStatusesIds,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $tipInvoiceSellerId,
        ?array $tipInvoiceSellerStatusesIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination and filters
     *
     * @param int|null $orderItemId
     * @param array|null $vybeTypesIds
     * @param string|null $buyer
     * @param string|null $seller
     * @param array|null $paymentMethodsIds
     * @param array|null $orderItemStatusesIds
     * @param int|null $tipInvoiceBuyerId
     * @param array|null $tipInvoiceBuyerStatusesIds
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $tipInvoiceSellerId
     * @param array|null $tipInvoiceSellerStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFiltered(
        ?int $orderItemId,
        ?array $vybeTypesIds,
        ?string $buyer,
        ?string $seller,
        ?array $paymentMethodsIds,
        ?array $orderItemStatusesIds,
        ?int $tipInvoiceBuyerId,
        ?array $tipInvoiceBuyerStatusesIds,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $tipInvoiceSellerId,
        ?array $tipInvoiceSellerStatusesIds,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with filters
     *
     * @param int|null $orderItemId
     * @param string|null $buyer
     * @param string|null $seller
     * @param array|null $paymentMethodsIds
     * @param array|null $orderItemStatusesIds
     * @param int|null $tipInvoiceBuyerId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $tipInvoiceSellerId
     *
     * @return Collection
     */
    public function getAllFilteredForAdminLabels(
        ?int $orderItemId,
        ?string $buyer,
        ?string $seller,
        ?array $paymentMethodsIds,
        ?array $orderItemStatusesIds,
        ?int $tipInvoiceBuyerId,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $tipInvoiceSellerId
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param OrderItem $orderItem
     * @param PaymentMethod $paymentMethod
     * @param User $buyer
     * @param User $seller
     * @param float $amount
     * @param float $amountEarned
     * @param float|null $amountTax
     * @param float $amountTotal
     * @param float|null $handlingFee
     * @param float|null $paymentFee
     * @param float|null $paymentFeeTax
     * @param string|null $comment
     * @param string|null $paidAt
     *
     * @return Tip|null
     */
    public function store(
        OrderItem $orderItem,
        PaymentMethod $paymentMethod,
        User $buyer,
        User $seller,
        float $amount,
        float $amountEarned,
        ?float $amountTax,
        float $amountTotal,
        ?float $handlingFee,
        ?float $paymentFee,
        ?float $paymentFeeTax,
        ?string $comment,
        ?string $paidAt
    ) : ?Tip;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Tip $tip
     * @param OrderItem|null $orderItem
     * @param PaymentMethod|null $paymentMethod
     * @param User|null $buyer
     * @param User|null $seller
     * @param float|null $amount
     * @param float|null $amountEarned
     * @param float|null $amountTax
     * @param float|null $amountTotal
     * @param float|null $handlingFee
     * @param float|null $paymentFee
     * @param float|null $paymentFeeTax
     * @param string|null $comment
     * @param string|null $paidAt
     *
     * @return Tip
     */
    public function update(
        Tip $tip,
        ?OrderItem $orderItem,
        ?PaymentMethod $paymentMethod,
        ?User $buyer,
        ?User $seller,
        ?float $amount,
        ?float $amountEarned,
        ?float $amountTax,
        ?float $amountTotal,
        ?float $handlingFee,
        ?float $paymentFee,
        ?float $paymentFeeTax,
        ?string $comment,
        ?string $paidAt
    ) : Tip;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Tip $tip
     * @param float $amount
     *
     * @return Tip
     */
    public function updateAmount(
        Tip $tip,
        float $amount
    ) : Tip;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Tip $tip
     *
     * @return Tip
     */
    public function updatePaidAt(
        Tip $tip
    ) : Tip;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Tip $tip
     *
     * @return bool
     */
    public function delete(
        Tip $tip
    ) : bool;
}
