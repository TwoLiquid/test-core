<?php

namespace App\Repositories\Order\Interfaces;

use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusListItem;
use App\Lists\Order\Item\Purchase\SortBy\OrderItemPurchaseSortByListItem;
use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Lists\Vybe\Appearance\VybeAppearanceListItem;
use App\Lists\Vybe\Type\VybeTypeListItem;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Timeslot;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface OrderItemRepositoryInterface
 *
 * @package App\Repositories\Order\Interfaces
 */
interface OrderItemRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return OrderItem|null
     */
    public function findById(
        ?int $id
    ) : ?OrderItem;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param int|null $id
     *
     * @return OrderItem|null
     */
    public function findFullById(
        ?int $id
    ) : ?OrderItem;

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
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param Order $order
     *
     * @return Collection
     */
    public function getByOrder(
        Order $order
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param string|null $seller
     * @param string|null $vybeTitle
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
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
        ?string $vybeTitle,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
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
     * @param string|null $vybeTitle
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
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
        ?string $vybeTitle,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
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
     * @param string|null $vybeTitle
     *
     * @return Collection
     */
    public function getAllFilteredForAdminLabels(
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?string $seller,
        ?string $vybeTitle
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $buyer
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $seller
     * @param int|null $vybeVersion
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllForBuyerFiltered(
        User $buyer,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $seller,
        ?int $vybeVersion,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
        ?array $orderItemPaymentStatusesIds,
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
     * @param string|null $seller
     * @param int|null $vybeVersion
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
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
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $seller,
        ?int $vybeVersion,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
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
     * @param User $buyer
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $seller
     * @param int|null $vybeVersion
     *
     * @return Collection
     */
    public function getAllForBuyerFilteredForAdminLabels(
        User $buyer,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $seller,
        ?int $vybeVersion
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
     * @param int|null $vybeVersion
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllForSellerFiltered(
        User $seller,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?int $vybeVersion,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
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
     * @param int|null $vybeVersion
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemStatusesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllForSellerPaginatedFiltered(
        User $seller,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?int $vybeVersion,
        ?array $vybeTypesIds,
        ?array $orderItemStatusesIds,
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
     * @param int|null $vybeVersion
     *
     * @return Collection
     */
    public function getAllForSellerFilteredForAdminLabels(
        User $seller,
        ?int $id,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $buyer,
        ?int $vybeVersion
    ) : Collection;

    /**
     * This method provides getting rows numbers
     * with an eloquent model by certain query
     *
     * @param array $orderItemsIds
     *
     * @return OrderItem
     */
    public function getStatusesByIdsCount(
        array $orderItemsIds
    ) : OrderItem;

    /**
     * This method provides getting rows numbers
     * with an eloquent model by certain query
     *
     * @param array $orderItemsIds
     *
     * @return OrderItem
     */
    public function getPaymentStatusesByIdsCount(
        array $orderItemsIds
    ) : OrderItem;

    /**
     * This method provides getting rows numbers
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getForSellerStatistic(
        User $user
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $seller
     * @param VybeAppearanceListItem|null $vybeAppearanceListItem
     * @param VybeTypeListItem|null $vybeTypeListItem
     * @param Activity|null $activity
     * @param OrderItemStatusListItem|null $orderItemStatusListItem
     * @param OrderItemPurchaseSortByListItem|null $orderItemPurchaseSortByListItem
     * @param string|null $vybeTitle
     * @param string|null $username
     * @param string|null $datetimeFrom
     * @param string|null $datetimeTo
     * @param int|null $amountFrom
     * @param int|null $amountTo
     * @param int|null $quantity
     * @param bool|null $onlyOpen
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllSalesFiltered(
        User $seller,
        ?VybeAppearanceListItem $vybeAppearanceListItem,
        ?VybeTypeListItem $vybeTypeListItem,
        ?Activity $activity,
        ?OrderItemStatusListItem $orderItemStatusListItem,
        ?OrderItemPurchaseSortByListItem $orderItemPurchaseSortByListItem,
        ?string $vybeTitle,
        ?string $username,
        ?string $datetimeFrom,
        ?string $datetimeTo,
        ?int $amountFrom,
        ?int $amountTo,
        ?int $quantity,
        ?bool $onlyOpen,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows with eloquent
     * model by certain query with pagination
     *
     * @param User $seller
     * @param VybeAppearanceListItem|null $vybeAppearanceListItem
     * @param VybeTypeListItem|null $vybeTypeListItem
     * @param Activity|null $activity
     * @param OrderItemStatusListItem|null $orderItemStatusListItem
     * @param OrderItemPurchaseSortByListItem|null $orderItemPurchaseSortByListItem
     * @param string|null $vybeTitle
     * @param string|null $username
     * @param string|null $datetimeFrom
     * @param string|null $datetimeTo
     * @param int|null $amountFrom
     * @param int|null $amountTo
     * @param int|null $quantity
     * @param bool|null $onlyOpen
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllSalesPaginatedFiltered(
        User $seller,
        ?VybeAppearanceListItem $vybeAppearanceListItem,
        ?VybeTypeListItem $vybeTypeListItem,
        ?Activity $activity,
        ?OrderItemStatusListItem $orderItemStatusListItem,
        ?OrderItemPurchaseSortByListItem $orderItemPurchaseSortByListItem,
        ?string $vybeTitle,
        ?string $username,
        ?string $datetimeFrom,
        ?string $datetimeTo,
        ?int $amountFrom,
        ?int $amountTo,
        ?int $quantity,
        ?bool $onlyOpen,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $buyer
     * @param VybeAppearanceListItem|null $vybeAppearanceListItem
     * @param VybeTypeListItem|null $vybeTypeListItem
     * @param Activity|null $activity
     * @param OrderItemStatusListItem|null $orderItemStatusListItem
     * @param OrderItemPurchaseSortByListItem|null $orderItemPurchaseSortByListItem
     * @param string|null $vybeTitle
     * @param string|null $username
     * @param string|null $datetimeFrom
     * @param string|null $datetimeTo
     * @param int|null $amountFrom
     * @param int|null $amountTo
     * @param int|null $quantity
     * @param bool|null $onlyOpen
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllPurchasesFiltered(
        User $buyer,
        ?VybeAppearanceListItem $vybeAppearanceListItem,
        ?VybeTypeListItem $vybeTypeListItem,
        ?Activity $activity,
        ?OrderItemStatusListItem $orderItemStatusListItem,
        ?OrderItemPurchaseSortByListItem $orderItemPurchaseSortByListItem,
        ?string $vybeTitle,
        ?string $username,
        ?string $datetimeFrom,
        ?string $datetimeTo,
        ?int $amountFrom,
        ?int $amountTo,
        ?int $quantity,
        ?bool $onlyOpen,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows with eloquent
     * model by certain query with pagination
     *
     * @param User $buyer
     * @param VybeAppearanceListItem|null $vybeAppearanceListItem
     * @param VybeTypeListItem|null $vybeTypeListItem
     * @param Activity|null $activity
     * @param OrderItemStatusListItem|null $orderItemStatusListItem
     * @param OrderItemPurchaseSortByListItem|null $orderItemPurchaseSortByListItem
     * @param string|null $vybeTitle
     * @param string|null $username
     * @param string|null $datetimeFrom
     * @param string|null $datetimeTo
     * @param int|null $amountFrom
     * @param int|null $amountTo
     * @param int|null $quantity
     * @param bool|null $onlyOpen
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPurchasesPaginatedFiltered(
        User $buyer,
        ?VybeAppearanceListItem $vybeAppearanceListItem,
        ?VybeTypeListItem $vybeTypeListItem,
        ?Activity $activity,
        ?OrderItemStatusListItem $orderItemStatusListItem,
        ?OrderItemPurchaseSortByListItem $orderItemPurchaseSortByListItem,
        ?string $vybeTitle,
        ?string $username,
        ?string $datetimeFrom,
        ?string $datetimeTo,
        ?int $amountFrom,
        ?int $amountTo,
        ?int $quantity,
        ?bool $onlyOpen,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Order $order
     * @param Vybe $vybe
     * @param User $seller
     * @param AppearanceCase $appearanceCase
     * @param Timeslot $timeslot
     * @param OrderItemStatusListItem $orderItemStatusListItem
     * @param OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem
     * @param int $vybeVersion
     * @param float $price
     * @param int $quantity
     * @param float $amountEarned
     * @param float $amountTotal
     * @param float $amountTax
     * @param float $handlingFee
     *
     * @return OrderItem|null
     */
    public function store(
        Order $order,
        Vybe $vybe,
        User $seller,
        AppearanceCase $appearanceCase,
        Timeslot $timeslot,
        OrderItemStatusListItem $orderItemStatusListItem,
        OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem,
        int $vybeVersion,
        float $price,
        int $quantity,
        float $amountEarned,
        float $amountTotal,
        float $amountTax,
        float $handlingFee
    ) : ?OrderItem;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param OrderItem $orderItem
     * @param Order|null $order
     * @param User|null $seller
     * @param AppearanceCase|null $appearanceCase
     * @param Timeslot|null $timeslot
     * @param OrderItemStatusListItem|null $orderItemStatusListItem
     * @param OrderItemPaymentStatusListItem|null $orderItemPaymentStatusListItem
     * @param int|null $vybeVersion
     * @param float|null $price
     * @param int|null $quantity
     * @param float|null $amountEarned
     * @param float|null $amountTotal
     * @param float|null $amountTax
     * @param float|null $handlingFee
     *
     * @return OrderItem
     */
    public function update(
        OrderItem $orderItem,
        ?Order $order,
        ?User $seller,
        ?AppearanceCase $appearanceCase,
        ?Timeslot $timeslot,
        ?OrderItemStatusListItem $orderItemStatusListItem,
        ?OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem,
        ?int $vybeVersion,
        ?float $price,
        ?int $quantity,
        ?float $amountEarned,
        ?float $amountTotal,
        ?float $amountTax,
        ?float $handlingFee
    ) : OrderItem;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param OrderItem $orderItem
     * @param Timeslot $timeslot
     *
     * @return OrderItem
     */
    public function updateTimeslot(
        OrderItem $orderItem,
        Timeslot $timeslot
    ) : OrderItem;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param OrderItem $orderItem
     * @param OrderItemStatusListItem $orderItemStatusListItem
     *
     * @return OrderItem
     */
    public function updateStatus(
        OrderItem $orderItem,
        OrderItemStatusListItem $orderItemStatusListItem
    ) : OrderItem;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param OrderItem $orderItem
     * @param OrderItemStatusListItem $orderItemStatusListItem
     *
     * @return OrderItem
     */
    public function updatePreviousStatus(
        OrderItem $orderItem,
        OrderItemStatusListItem $orderItemStatusListItem
    ) : OrderItem;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param OrderItem $orderItem
     * @param OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem
     *
     * @return OrderItem
     */
    public function updatePaymentStatus(
        OrderItem $orderItem,
        OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem
    ) : OrderItem;

    /**
     * This method provides updating existing rows
     * with an eloquent model
     *
     * @param OrderItem $orderItem
     * @param OrderItemStatusListItem $orderItemStatusListItem
     * @param OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem
     *
     * @return OrderItem
     */
    public function updateStatuses(
        OrderItem $orderItem,
        OrderItemStatusListItem $orderItemStatusListItem,
        OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem
    ) : OrderItem;

    /**
     * This method provides updating existing rows
     * with an eloquent model
     *
     * @param OrderItem $orderItem
     * @param Carbon $expiredAt
     *
     * @return OrderItem
     */
    public function updateExpiredAt(
        OrderItem $orderItem,
        Carbon $expiredAt
    ) : OrderItem;

    /**
     * This method provides updating existing rows
     * with an eloquent model
     *
     * @param OrderItem $orderItem
     * @param Carbon $acceptedAt
     *
     * @return OrderItem
     */
    public function updateAcceptedAt(
        OrderItem $orderItem,
        Carbon $acceptedAt
    ) : OrderItem;

    /**
     * This method provides updating existing rows
     * with an eloquent model
     *
     * @param OrderItem $orderItem
     * @param Carbon $finishedAt
     *
     * @return OrderItem
     */
    public function updateFinishedAt(
        OrderItem $orderItem,
        Carbon $finishedAt
    ) : OrderItem;

    /**
     * This method provides attaching an existing model
     * with a current model with belongs to many relations
     *
     * @param OrderItem $orderItem
     * @param OrderInvoice $orderInvoice
     */
    public function attachInvoice(
        OrderItem $orderItem,
        OrderInvoice $orderInvoice
    ) : void;

    /**
     * This method provides attaching existing rows
     * with a current model with belongs to many relations
     *
     * @param OrderItem $orderItem
     * @param array $invoicesIds
     * @param bool|null $detaching
     */
    public function attachInvoices(
        OrderItem $orderItem,
        array $invoicesIds,
        ?bool $detaching
    ) : void;

    /**
     * This method provides detaching existing row
     * with a current model with belongs to many relations
     *
     * @param OrderItem $orderItem
     * @param OrderInvoice $orderInvoice
     */
    public function detachInvoice(
        OrderItem $orderItem,
        OrderInvoice $orderInvoice
    ) : void;

    /**
     * This method provides detaching existing rows
     * with a current model with belongs to many relations
     *
     * @param OrderItem $orderItem
     * @param array $invoicesIds
     */
    public function detachInvoices(
        OrderItem $orderItem,
        array $invoicesIds
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param OrderItem $orderItem
     *
     * @return bool
     */
    public function delete(
        OrderItem $orderItem
    ) : bool;
}
