<?php

namespace App\Repositories\Tip\Interfaces;

use App\Lists\Invoice\Status\InvoiceStatusListItem;
use App\Lists\Invoice\Type\InvoiceTypeListItem;
use App\Models\MySql\Tip\Tip;
use App\Models\MySql\Tip\TipInvoice;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface TipInvoiceRepositoryInterface
 *
 * @package App\Repositories\Tip\Interfaces
 */
interface TipInvoiceRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return TipInvoice|null
     */
    public function findById(
        ?int $id
    ) : ?TipInvoice;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return TipInvoice|null
     */
    public function findFullById(
        ?int $id
    ) : ?TipInvoice;

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
     * @param InvoiceTypeListItem $invoiceTypeListItem
     * @param int|null $orderItemId
     * @param int|null $vybeTypeId
     * @param string|null $buyer
     * @param string|null $seller
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $orderItemStatusesIds
     * @param int|null $tipInvoiceId
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFiltered(
        InvoiceTypeListItem $invoiceTypeListItem,
        ?int $orderItemId,
        ?int $vybeTypeId,
        ?string $buyer,
        ?string $seller,
        ?string $dateFrom,
        ?string $dateTo,
        ?array $orderItemStatusesIds,
        ?int $tipInvoiceId,
        ?array $invoiceStatusesIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination and filters
     *
     * @param InvoiceTypeListItem $invoiceTypeListItem
     * @param int|null $orderItemId
     * @param int|null $vybeTypeId
     * @param string|null $buyer
     * @param string|null $seller
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $orderItemStatusesIds
     * @param int|null $tipInvoiceId
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFiltered(
        InvoiceTypeListItem $invoiceTypeListItem,
        ?int $orderItemId,
        ?int $vybeTypeId,
        ?string $buyer,
        ?string $seller,
        ?string $dateFrom,
        ?string $dateTo,
        ?array $orderItemStatusesIds,
        ?int $tipInvoiceId,
        ?array $invoiceStatusesIds,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with filters
     *
     * @param InvoiceTypeListItem $invoiceTypeListItem
     * @param int|null $orderItemId
     * @param int|null $vybeTypeId
     * @param string|null $buyer
     * @param string|null $seller
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $tipInvoiceId
     *
     * @return Collection
     */
    public function getAllFilteredForAdminLabels(
        InvoiceTypeListItem $invoiceTypeListItem,
        ?int $orderItemId,
        ?int $vybeTypeId,
        ?string $buyer,
        ?string $seller,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $tipInvoiceId
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with filters
     *
     * @param User $buyer
     * @param int|null $orderItemId
     * @param int|null $vybeTypeId
     * @param string|null $seller
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $orderItemStatusesIds
     * @param int|null $tipInvoiceId
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFilteredForBuyer(
        User $buyer,
        ?int $orderItemId,
        ?int $vybeTypeId,
        ?string $seller,
        ?string $dateFrom,
        ?string $dateTo,
        ?array $orderItemStatusesIds,
        ?int $tipInvoiceId,
        ?array $invoiceStatusesIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination and filters
     *
     * @param User $buyer
     * @param int|null $orderItemId
     * @param int|null $vybeTypeId
     * @param string|null $seller
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $orderItemStatusesIds
     * @param int|null $tipInvoiceId
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFilteredForBuyer(
        User $buyer,
        ?int $orderItemId,
        ?int $vybeTypeId,
        ?string $seller,
        ?string $dateFrom,
        ?string $dateTo,
        ?array $orderItemStatusesIds,
        ?int $tipInvoiceId,
        ?array $invoiceStatusesIds,
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
     * @param int|null $orderItemId
     * @param int|null $vybeTypeId
     * @param string|null $seller
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $tipInvoiceId
     *
     * @return Collection
     */
    public function getAllFilteredForBuyerForAdminLabels(
        User $buyer,
        ?int $orderItemId,
        ?int $vybeTypeId,
        ?string $seller,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $tipInvoiceId
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with filters
     *
     * @param User $seller
     * @param int|null $orderItemId
     * @param int|null $vybeTypeId
     * @param string|null $buyer
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $orderItemStatusesIds
     * @param int|null $tipInvoiceId
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFilteredForSeller(
        User $seller,
        ?int $orderItemId,
        ?int $vybeTypeId,
        ?string $buyer,
        ?string $dateFrom,
        ?string $dateTo,
        ?array $orderItemStatusesIds,
        ?int $tipInvoiceId,
        ?array $invoiceStatusesIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination and filters
     *
     * @param User $seller
     * @param int|null $orderItemId
     * @param int|null $vybeTypeId
     * @param string|null $buyer
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $orderItemStatusesIds
     * @param int|null $tipInvoiceId
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFilteredForSeller(
        User $seller,
        ?int $orderItemId,
        ?int $vybeTypeId,
        ?string $buyer,
        ?string $dateFrom,
        ?string $dateTo,
        ?array $orderItemStatusesIds,
        ?int $tipInvoiceId,
        ?array $invoiceStatusesIds,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with filters
     *
     * @param User $seller
     * @param int|null $orderItemId
     * @param int|null $vybeTypeId
     * @param string|null $buyer
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $tipInvoiceId
     *
     * @return Collection
     */
    public function getAllFilteredForSellerForAdminLabels(
        User $seller,
        ?int $orderItemId,
        ?int $vybeTypeId,
        ?string $buyer,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $tipInvoiceId
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param InvoiceTypeListItem $invoiceTypeListItem
     * @param array $tipInvoicesIds
     *
     * @return TipInvoice
     */
    public function getStatusesByIdsCount(
        InvoiceTypeListItem $invoiceTypeListItem,
        array $tipInvoicesIds
    ) : TipInvoice;

    /**
     * This method provides getting all rows
     * with an eloquent model with filters
     *
     * @param User $buyer
     * @param int|null $orderItemId
     * @param int|null $invoiceId
     * @param string|null $username
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $amount
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
     * @param array|null $invoiceStatusesIds
     *
     * @return Collection
     */
    public function getForDashboardBuyerFiltered(
        User $buyer,
        ?int $orderItemId,
        ?int $invoiceId,
        ?string $username,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $amount,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
        ?array $invoiceStatusesIds
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination and filters
     *
     * @param User $buyer
     * @param int|null $orderItemId
     * @param int|null $invoiceId
     * @param string|null $username
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $amount
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getForDashboardBuyerFilteredPaginated(
        User $buyer,
        ?int $orderItemId,
        ?int $invoiceId,
        ?string $username,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $amount,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
        ?array $invoiceStatusesIds,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with filters
     *
     * @param User $seller
     * @param int|null $orderItemId
     * @param int|null $invoiceId
     * @param string|null $username
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $amount
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
     * @param array|null $invoiceStatusesIds
     *
     * @return Collection
     */
    public function getForDashboardSellerFiltered(
        User $seller,
        ?int $orderItemId,
        ?int $invoiceId,
        ?string $username,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $amount,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
        ?array $invoiceStatusesIds
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination and filters
     *
     * @param User $seller
     * @param int|null $orderItemId
     * @param int|null $invoiceId
     * @param string|null $username
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $amount
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getForDashboardSellerFilteredPaginated(
        User $seller,
        ?int $orderItemId,
        ?int $invoiceId,
        ?string $username,
        ?string $dateFrom,
        ?string $dateTo,
        ?int $amount,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
        ?array $invoiceStatusesIds,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Tip $tip
     * @param InvoiceTypeListItem $invoiceTypeListItem
     * @param InvoiceStatusListItem $invoiceStatusListItem
     *
     * @return TipInvoice|null
     */
    public function store(
        Tip $tip,
        InvoiceTypeListItem $invoiceTypeListItem,
        InvoiceStatusListItem $invoiceStatusListItem
    ) : ?TipInvoice;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param TipInvoice $tipInvoice
     * @param Tip $tip
     * @param InvoiceTypeListItem|null $invoiceTypeListItem
     * @param InvoiceStatusListItem|null $invoiceStatusListItem
     *
     * @return TipInvoice
     */
    public function update(
        TipInvoice $tipInvoice,
        Tip $tip,
        ?InvoiceTypeListItem $invoiceTypeListItem,
        ?InvoiceStatusListItem $invoiceStatusListItem
    ) : TipInvoice;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param TipInvoice $tipInvoice
     * @param InvoiceStatusListItem $invoiceStatusListItem
     *
     * @return TipInvoice
     */
    public function updateStatus(
        TipInvoice $tipInvoice,
        InvoiceStatusListItem $invoiceStatusListItem
    ) : TipInvoice;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param TipInvoice $tipInvoice
     *
     * @return bool
     */
    public function delete(
        TipInvoice $tipInvoice
    ) : bool;
}
