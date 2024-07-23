<?php

namespace App\Repositories\Order\Interfaces;

use App\Lists\Invoice\Status\InvoiceStatusListItem;
use App\Lists\Invoice\Type\InvoiceTypeListItem;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface OrderInvoiceRepositoryInterface
 *
 * @package App\Repositories\Order\Interfaces
 */
interface OrderInvoiceRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary keys
     *
     * @param int|null $id
     *
     * @return OrderInvoice|null
     */
    public function findById(
        ?int $id
    ) : ?OrderInvoice;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param InvoiceTypeListItem|null $invoiceTypeListItem
     * @param int|null $id
     *
     * @return OrderInvoice|null
     */
    public function findFullById(
        ?InvoiceTypeListItem $invoiceTypeListItem,
        ?int $id
    ) : ?OrderInvoice;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Order $order
     *
     * @return OrderInvoice|null
     */
    public function findByOrderForBuyer(
        Order $order
    ) : ?OrderInvoice;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllForBuyerCount() : int;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllForSellerCount() : int;

    /**
     * This method provides getting rows number
     * with an eloquent model by certain query
     *
     * @return int
     */
    public function getAllForAffiliateCount() : int;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param InvoiceTypeListItem|null $invoiceTypeListItem
     *
     * @return Collection
     */
    public function getAll(
        ?InvoiceTypeListItem $invoiceTypeListItem
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
     *
     * @param InvoiceTypeListItem|null $invoiceTypeListItem
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?InvoiceTypeListItem $invoiceTypeListItem,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $orderOverviewId
     * @param string|null $buyer
     * @param string|null $seller
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getFilteredForBuyer(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $orderOverviewId,
        ?string $buyer,
        ?string $seller,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
        ?array $invoiceStatusesIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $vybeVersion
     * @param string|null $buyer
     * @param string|null $seller
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getFilteredForSeller(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $vybeVersion,
        ?string $buyer,
        ?string $seller,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
        ?array $invoiceStatusesIds,
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
     * @param int|null $orderOverviewId
     * @param string|null $buyer
     * @param string|null $seller
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getPaginatedFilteredForBuyer(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $orderOverviewId,
        ?string $buyer,
        ?string $seller,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
        ?array $invoiceStatusesIds,
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
     * @param int|null $orderOverviewId
     * @param string|null $buyer
     * @param string|null $seller
     *
     * @return Collection
     */
    public function getFilteredForBuyerForAdminLabels(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $orderOverviewId,
        ?string $buyer,
        ?string $seller
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
     *
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $vybeVersion
     * @param string|null $buyer
     * @param string|null $seller
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getPaginatedFilteredForSeller(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $vybeVersion,
        ?string $buyer,
        ?string $seller,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
        ?array $invoiceStatusesIds,
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
     * @param int|null $vybeVersion
     * @param string|null $buyer
     * @param string|null $seller
     *
     * @return Collection
     */
    public function getFilteredForSellerForAdminLabels(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $vybeVersion,
        ?string $buyer,
        ?string $seller
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $buyer
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $orderOverviewId
     * @param string|null $seller
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getFilteredByUserForBuyer(
        User $buyer,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $orderOverviewId,
        ?string $seller,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
        ?array $invoiceStatusesIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
     *
     * @param User $buyer
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $orderOverviewId
     * @param string|null $seller
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getPaginatedFilteredByUserForBuyer(
        User $buyer,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $orderOverviewId,
        ?string $seller,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
        ?array $invoiceStatusesIds,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $buyer
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $orderOverviewId
     * @param string|null $seller
     *
     * @return Collection
     */
    public function getFilteredByUserForBuyerForAdminLabels(
        User $buyer,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $orderOverviewId,
        ?string $seller
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $seller
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getFilteredByUserForSeller(
        User $seller,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
        ?array $invoiceStatusesIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
     *
     * @param User $seller
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getPaginatedFilteredByUserForSeller(
        User $seller,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
        ?array $invoiceStatusesIds,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $seller
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     *
     * @return Collection
     */
    public function getFilteredByUserForSellerForAdminLabels(
        User $seller,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer
    ) : Collection;

    /**
     * This method provides getting rows numbers
     * with an eloquent model by certain query
     *
     * @param array $orderInvoicesIds
     *
     * @return OrderInvoice
     */
    public function getForBuyerStatusesByIdsCount(
        array $orderInvoicesIds
    ) : OrderInvoice;

    /**
     * This method provides getting rows numbers
     * with an eloquent model by certain query
     *
     * @param array $orderInvoicesIds
     *
     * @return OrderInvoice
     */
    public function getForSellerStatusesByIdsCount(
        array $orderInvoicesIds
    ) : OrderInvoice;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $buyer
     * @param int|null $invoiceId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $total
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     *
     * @return Collection
     */
    public function getForDashboardBuyerFiltered(
        User $buyer,
        ?int $invoiceId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $total,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
        ?array $invoiceStatusesIds
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
     *
     * @param User $buyer
     * @param int|null $invoiceId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $total
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getForDashboardBuyerFilteredPaginated(
        User $buyer,
        ?int $invoiceId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $total,
        ?array $vybeTypesIds,
        ?array $orderItemPaymentStatusesIds,
        ?array $invoiceStatusesIds,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $seller
     * @param int|null $invoiceId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $earned
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
     * @param array|null $invoiceStatusesIds
     *
     * @return Collection
     */
    public function getForDashboardSellerFiltered(
        User $seller,
        ?int $invoiceId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $earned,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
        ?array $invoiceStatusesIds
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     * with pagination
     *
     * @param User $seller
     * @param int|null $invoiceId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $earned
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getForDashboardSellerFilteredPaginated(
        User $seller,
        ?int $invoiceId,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $earned,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
        ?array $invoiceStatusesIds,
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param OrderInvoice|null $parentInvoice
     * @param Order $order
     * @param InvoiceTypeListItem $invoiceTypeListItem
     * @param InvoiceStatusListItem $invoiceStatusListItem
     *
     * @return OrderInvoice|null
     */
    public function store(
        ?OrderInvoice $parentInvoice,
        Order $order,
        InvoiceTypeListItem $invoiceTypeListItem,
        InvoiceStatusListItem $invoiceStatusListItem
    ) : ?OrderInvoice;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param OrderInvoice $orderInvoice
     * @param OrderInvoice|null $parentInvoice
     * @param Order|null $order
     * @param InvoiceTypeListItem|null $invoiceTypeListItem
     * @param InvoiceStatusListItem|null $invoiceStatusListItem
     *
     * @return OrderInvoice
     */
    public function update(
        OrderInvoice $orderInvoice,
        ?OrderInvoice $parentInvoice,
        ?Order $order,
        ?InvoiceTypeListItem $invoiceTypeListItem,
        ?InvoiceStatusListItem $invoiceStatusListItem
    ) : OrderInvoice;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param OrderInvoice $orderInvoice
     * @param InvoiceStatusListItem $invoiceStatusListItem
     *
     * @return OrderInvoice
     */
    public function updateStatus(
        OrderInvoice $orderInvoice,
        InvoiceStatusListItem $invoiceStatusListItem
    ) : OrderInvoice;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param OrderInvoice $orderInvoice
     * @param OrderItem $orderItem
     */
    public function attachOrderItem(
        OrderInvoice $orderInvoice,
        OrderItem $orderItem
    ) : void;

    /**
     * This method provides attaching existing rows
     * with a current model with belongs to many relations
     *
     * @param OrderInvoice $orderInvoice
     * @param array $orderItemsIds
     * @param bool|null $detaching
     */
    public function attachOrderItems(
        OrderInvoice $orderInvoice,
        array $orderItemsIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing row
     * with a current model with belongs to many relations
     *
     * @param OrderInvoice $orderInvoice
     * @param OrderItem $orderItem
     */
    public function detachOrderItem(
        OrderInvoice $orderInvoice,
        OrderItem $orderItem
    ) : void;

    /**
     * This method provides detaching existing rows
     * with a current model with belongs to many relations
     *
     * @param OrderInvoice $orderInvoice
     * @param array $orderItemsIds
     */
    public function detachOrderItems(
        OrderInvoice $orderInvoice,
        array $orderItemsIds
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param OrderInvoice $orderInvoice
     *
     * @return bool
     */
    public function delete(
        OrderInvoice $orderInvoice
    ) : bool;
}
