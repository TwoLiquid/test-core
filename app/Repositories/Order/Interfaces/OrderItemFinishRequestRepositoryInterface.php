<?php

namespace App\Repositories\Order\Interfaces;

use App\Lists\Order\Item\Request\Action\OrderItemRequestActionListItem;
use App\Lists\Order\Item\Request\Initiator\OrderItemRequestInitiatorListItem;
use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusListItem;
use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Models\MongoDb\Order\Request\OrderItemFinishRequest;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface OrderItemFinishRequestRepositoryInterface
 *
 * @package App\Repositories\Order\Interfaces
 */
interface OrderItemFinishRequestRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return OrderItemFinishRequest|null
     */
    public function findById(
        ?string $id
    ) : ?OrderItemFinishRequest;

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
     * with an eloquent model
     *
     * @param int|null $orderItemId
     * @param string|null $fromRequestDatetime
     * @param string|null $toRequestDatetime
     * @param string|null $vybeTitle
     * @param string|null $orderDateFrom
     * @param string|null $orderDateTo
     * @param string|null $buyer
     * @param string|null $seller
     * @param array|null $fromOrderItemStatusesIds
     * @param array|null $toOrderItemStatusesIds
     * @param array|null $orderItemRequestActionIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     */
    public function getAllFiltered(
        ?int $orderItemId,
        ?string $fromRequestDatetime,
        ?string $toRequestDatetime,
        ?string $vybeTitle,
        ?string $orderDateFrom,
        ?string $orderDateTo,
        ?string $buyer,
        ?string $seller,
        ?array $fromOrderItemStatusesIds,
        ?array $toOrderItemStatusesIds,
        ?array $orderItemRequestActionIds,
        ?string $sortBy,
        ?string $sortOrder
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $orderItemId
     * @param string|null $fromRequestDatetime
     * @param string|null $toRequestDatetime
     * @param string|null $vybeTitle
     * @param string|null $orderDateFrom
     * @param string|null $orderDateTo
     * @param string|null $buyer
     * @param string|null $seller
     * @param array|null $fromOrderItemStatusesIds
     * @param array|null $toOrderItemStatusesIds
     * @param array|null $orderItemRequestActionIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginatedFiltered(
        ?int $orderItemId,
        ?string $fromRequestDatetime,
        ?string $toRequestDatetime,
        ?string $vybeTitle,
        ?string $orderDateFrom,
        ?string $orderDateTo,
        ?string $buyer,
        ?string $seller,
        ?array $fromOrderItemStatusesIds,
        ?array $toOrderItemStatusesIds,
        ?array $orderItemRequestActionIds,
        ?string $sortBy,
        ?string $sortOrder,
        ?int $perPage,
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @param int|null $orderItemId
     * @param string|null $fromRequestDatetime
     * @param string|null $toRequestDatetime
     * @param string|null $vybeTitle
     * @param string|null $orderDateFrom
     * @param string|null $orderDateTo
     * @param string|null $buyer
     * @param string|null $seller
     *
     * @return Collection
     */
    public function getAllFilteredForAdminLabels(
        ?int $orderItemId,
        ?string $fromRequestDatetime,
        ?string $toRequestDatetime,
        ?string $vybeTitle,
        ?string $orderDateFrom,
        ?string $orderDateTo,
        ?string $buyer,
        ?string $seller
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param OrderItemRequestStatusListItem $orderItemRequestStatusListItem
     *
     * @return int
     */
    public function getRequestStatusCount(
        OrderItemRequestStatusListItem $orderItemRequestStatusListItem
    ) : int;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param array $ids
     * @param OrderItemRequestStatusListItem $orderItemRequestStatusListItem
     *
     * @return int
     */
    public function getRequestStatusCountByIds(
        array $ids,
        OrderItemRequestStatusListItem $orderItemRequestStatusListItem
    ) : int;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param OrderItemStatusListItem $orderItemStatusListItem
     *
     * @return int
     */
    public function getToOrderItemStatusCount(
        OrderItemStatusListItem $orderItemStatusListItem
    ) : int;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param array $ids
     * @param OrderItemStatusListItem $orderItemStatusListItem
     *
     * @return int
     */
    public function getToOrderItemStatusCountByIds(
        array $ids,
        OrderItemStatusListItem $orderItemStatusListItem
    ) : int;

    /**
     * This method provides creating row
     * with an eloquent model
     *
     * @param OrderItem $orderItem
     * @param User $buyer
     * @param User $seller
     * @param User $opening
     * @param OrderItemRequestInitiatorListItem $orderItemRequestInitiatorListItem
     * @param OrderItemStatusListItem $fromOrderItemStatusListItem
     * @param OrderItemStatusListItem|null $toOrderItemStatusListItem
     * @param OrderItemRequestActionListItem|null $orderItemRequestActionListItem
     * @param string|null $fromRequestDatetime
     * @param string|null $toRequestDatetime
     *
     * @return OrderItemFinishRequest|null
     */
    public function store(
        OrderItem $orderItem,
        User $buyer,
        User $seller,
        User $opening,
        OrderItemRequestInitiatorListItem $orderItemRequestInitiatorListItem,
        OrderItemStatusListItem $fromOrderItemStatusListItem,
        ?OrderItemStatusListItem $toOrderItemStatusListItem,
        ?OrderItemRequestActionListItem $orderItemRequestActionListItem,
        ?string $fromRequestDatetime,
        ?string $toRequestDatetime
    ) : ?OrderItemFinishRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param OrderItemFinishRequest $orderItemFinishRequest
     * @param OrderItem|null $orderItem
     * @param User|null $buyer
     * @param User|null $seller
     * @param User|null $opening
     * @param User|null $closing
     * @param OrderItemRequestInitiatorListItem|null $orderItemRequestInitiatorListItem
     * @param OrderItemStatusListItem|null $fromOrderItemStatusListItem
     * @param OrderItemStatusListItem|null $toOrderItemStatusListItem
     * @param OrderItemRequestActionListItem|null $orderItemRequestActionListItem
     * @param OrderItemRequestStatusListItem|null $orderItemRequestStatusListItem
     * @param string|null $fromRequestDatetime
     * @param string|null $toRequestDatetime
     *
     * @return OrderItemFinishRequest
     */
    public function update(
        OrderItemFinishRequest $orderItemFinishRequest,
        ?OrderItem $orderItem,
        ?User $buyer,
        ?User $seller,
        ?User $opening,
        ?User $closing,
        ?OrderItemRequestInitiatorListItem $orderItemRequestInitiatorListItem,
        ?OrderItemStatusListItem $fromOrderItemStatusListItem,
        ?OrderItemStatusListItem $toOrderItemStatusListItem,
        ?OrderItemRequestActionListItem $orderItemRequestActionListItem,
        ?OrderItemRequestStatusListItem $orderItemRequestStatusListItem,
        ?string $fromRequestDatetime,
        ?string $toRequestDatetime
    ) : OrderItemFinishRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param OrderItemFinishRequest $orderItemFinishRequest
     * @param User|null $closing
     * @param OrderItemStatusListItem|null $toOrderItemStatusListItem
     * @param OrderItemRequestActionListItem|null $orderItemRequestActionListItem
     *
     * @return OrderItemFinishRequest
     */
    public function updateWhenClose(
        OrderItemFinishRequest $orderItemFinishRequest,
        ?User $closing,
        ?OrderItemStatusListItem $toOrderItemStatusListItem,
        ?OrderItemRequestActionListItem $orderItemRequestActionListItem
    ) : OrderItemFinishRequest;

    /**
     * This method provides updating row
     * with an eloquent model
     *
     * @param OrderItemFinishRequest $orderItemFinishRequest
     * @param OrderItemRequestActionListItem $orderItemRequestActionListItem
     *
     * @return OrderItemFinishRequest|null
     */
    public function updateAction(
        OrderItemFinishRequest $orderItemFinishRequest,
        OrderItemRequestActionListItem $orderItemRequestActionListItem
    ) : ?OrderItemFinishRequest;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return bool
     */
    public function delete(
        OrderItemFinishRequest $orderItemFinishRequest
    ) : bool;
}
