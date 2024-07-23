<?php

namespace App\Repositories\Order;

use App\Exceptions\DatabaseException;
use App\Lists\Order\Item\Request\Action\OrderItemRequestActionListItem;
use App\Lists\Order\Item\Request\Initiator\OrderItemRequestInitiatorListItem;
use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusList;
use App\Lists\Order\Item\Request\Status\OrderItemRequestStatusListItem;
use App\Lists\Order\Item\Status\OrderItemStatusListItem;
use App\Models\MongoDb\Order\Request\OrderItemFinishRequest;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Order\Interfaces\OrderItemFinishRequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class OrderItemFinishRequestRepository
 *
 * @package App\Repositories\Order
 */
class OrderItemFinishRequestRepository extends BaseRepository implements OrderItemFinishRequestRepositoryInterface
{
    /**
     * OrderItemFinishRequestRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.orderItemFinishRequest.cacheTime');
        $this->perPage = config('repositories.orderItemFinishRequest.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return OrderItemFinishRequest|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?OrderItemFinishRequest
    {
        try {
            return OrderItemFinishRequest::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getAllCount() : int
    {
        try {
            return Cache::remember('orderItemFinishRequests.all.count', $this->cacheTime,
                function () {
                    return OrderItemFinishRequest::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll() : Collection
    {
        try {
            return OrderItemFinishRequest::query()
                ->with([
                    'item',
                    'buyer',
                    'seller'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return OrderItemFinishRequest::query()
                ->with([
                    'item',
                    'buyer',
                    'seller'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        ?int $orderItemId = null,
        ?string $fromRequestDatetime = null,
        ?string $toRequestDatetime = null,
        ?string $vybeTitle = null,
        ?string $orderDateFrom = null,
        ?string $orderDateTo = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?array $fromOrderItemStatusesIds = null,
        ?array $toOrderItemStatusesIds = null,
        ?array $orderItemRequestActionIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return OrderItemFinishRequest::query()
                ->with([
                    'item.vybe' => function ($query) {
                        $query->select([
                            'id',
                            'title'
                        ]);
                    },
                    'buyer' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'seller' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->where('item_id', '=', $orderItemId);
                })
                ->when($fromRequestDatetime, function ($query) use ($fromRequestDatetime) {
                    $query->where('from_request_datetime', '>=', Carbon::parse($fromRequestDatetime));
                })
                ->when($toRequestDatetime, function ($query) use ($toRequestDatetime) {
                    $query->where('to_request_datetime', '<=', Carbon::parse($toRequestDatetime));
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->whereHas('item.vybe', function($query) use ($vybeTitle) {
                        $query->where('title', 'LIKE', '%'. $vybeTitle . '%');
                    });
                })
                ->when($orderDateFrom, function ($query) use ($orderDateFrom) {
                    $query->where('from_request_datetime', '>=', Carbon::parse($orderDateFrom));
                })
                ->when($orderDateTo, function ($query) use ($orderDateTo) {
                    $query->where('to_request_datetime', '<=', Carbon::parse($orderDateTo));
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('seller', function($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller . '%');
                    });
                })
                ->when($fromOrderItemStatusesIds, function ($query) use ($fromOrderItemStatusesIds) {
                    $query->whereIn('from_order_item_statuses_ids', $fromOrderItemStatusesIds);
                })
                ->when($toOrderItemStatusesIds, function ($query) use ($toOrderItemStatusesIds) {
                    $query->whereIn('to_order_item_statuses_ids', $toOrderItemStatusesIds);
                })
                ->when($orderItemRequestActionIds, function ($query) use ($orderItemRequestActionIds) {
                    $query->whereIn('order_item_request_action_ids', $orderItemRequestActionIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy('item_id', $sortOrder);
                    }

                    if ($sortBy == 'from_request_datetime') {
                        $query->orderBy('from_request_datetime', $sortOrder);
                    }

                    if ($sortBy == 'to_request_datetime') {
                        $query->orderBy('to_request_datetime', $sortOrder);
                    }

                    if ($sortBy == 'username') {
                        $query->orderBy('to_request_datetime', $sortOrder);
                    }

                    if ($sortBy == 'vybe_title') {
                        $query->orderBy(
                            Vybe::query()->select('title')
                                ->join('vybes', 'vybes.id', '=', 'order_items.vybe_id')
                                ->whereColumn('order_items.id', 'order_item_pending_requests.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy(
                            Order::query()->select('created_at')
                                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                ->whereColumn('order_items.id', 'order_item_pending_requests.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('users', 'users.id', '=', 'orders.buyer_id')
                                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                ->whereColumn('order_items.id', 'order_item_pending_requests.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('users', 'users.id', '=', 'order_items.seller_id')
                                ->whereColumn('order_items.id', 'order_item_pending_requests.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'initiator') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('users.id', 'order_item_pending_requests.initiator_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'from_order_item_statuses') {
                        $query->orderBy('from_order_item_status_id', $sortOrder);
                    }

                    if ($sortBy == 'from_order_item_statuses') {
                        $query->orderBy('to_order_item_status_id', $sortOrder);
                    }

                    if ($sortBy == 'order_item_request_action') {
                        $query->orderBy('order_item_request_action_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllPaginatedFiltered(
        ?int $orderItemId = null,
        ?string $fromRequestDatetime = null,
        ?string $toRequestDatetime = null,
        ?string $vybeTitle = null,
        ?string $orderDateFrom = null,
        ?string $orderDateTo = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?array $fromOrderItemStatusesIds = null,
        ?array $toOrderItemStatusesIds = null,
        ?array $orderItemRequestActionIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return OrderItemFinishRequest::query()
                ->with([
                    'item.vybe' => function ($query) {
                        $query->select([
                            'id',
                            'title'
                        ]);
                    },
                    'buyer' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'seller' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->where('item_id', '=', $orderItemId);
                })
                ->when($fromRequestDatetime, function ($query) use ($fromRequestDatetime) {
                    $query->where('from_request_datetime', '>=', Carbon::parse($fromRequestDatetime));
                })
                ->when($toRequestDatetime, function ($query) use ($toRequestDatetime) {
                    $query->where('to_request_datetime', '<=', Carbon::parse($toRequestDatetime));
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->whereHas('item.vybe', function($query) use ($vybeTitle) {
                        $query->where('title', 'LIKE', '%'. $vybeTitle . '%');
                    });
                })
                ->when($orderDateFrom, function ($query) use ($orderDateFrom) {
                    $query->where('from_request_datetime', '>=', Carbon::parse($orderDateFrom));
                })
                ->when($orderDateTo, function ($query) use ($orderDateTo) {
                    $query->where('to_request_datetime', '<=', Carbon::parse($orderDateTo));
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('seller', function($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller . '%');
                    });
                })
                ->when($fromOrderItemStatusesIds, function ($query) use ($fromOrderItemStatusesIds) {
                    $query->whereIn('from_order_item_statuses_ids', $fromOrderItemStatusesIds);
                })
                ->when($toOrderItemStatusesIds, function ($query) use ($toOrderItemStatusesIds) {
                    $query->whereIn('to_order_item_statuses_ids', $toOrderItemStatusesIds);
                })
                ->when($orderItemRequestActionIds, function ($query) use ($orderItemRequestActionIds) {
                    $query->whereIn('order_item_request_action_ids', $orderItemRequestActionIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy('item_id', $sortOrder);
                    }

                    if ($sortBy == 'from_request_datetime') {
                        $query->orderBy('from_request_datetime', $sortOrder);
                    }

                    if ($sortBy == 'to_request_datetime') {
                        $query->orderBy('to_request_datetime', $sortOrder);
                    }

                    if ($sortBy == 'username') {
                        $query->orderBy('to_request_datetime', $sortOrder);
                    }

                    if ($sortBy == 'vybe_title') {
                        $query->orderBy(
                            Vybe::query()->select('title')
                                ->join('vybes', 'vybes.id', '=', 'order_items.vybe_id')
                                ->whereColumn('order_items.id', 'order_item_pending_requests.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy(
                            Order::query()->select('created_at')
                                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                ->whereColumn('order_items.id', 'order_item_pending_requests.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('users', 'users.id', '=', 'orders.buyer_id')
                                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                ->whereColumn('order_items.id', 'order_item_pending_requests.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('users', 'users.id', '=', 'order_items.seller_id')
                                ->whereColumn('order_items.id', 'order_item_pending_requests.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'initiator') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('users.id', 'order_item_pending_requests.initiator_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'from_order_item_statuses') {
                        $query->orderBy('from_order_item_status_id', $sortOrder);
                    }

                    if ($sortBy == 'from_order_item_statuses') {
                        $query->orderBy('to_order_item_status_id', $sortOrder);
                    }

                    if ($sortBy == 'order_item_request_action') {
                        $query->orderBy('order_item_request_action_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForAdminLabels(
        ?int $orderItemId = null,
        ?string $fromRequestDatetime = null,
        ?string $toRequestDatetime = null,
        ?string $vybeTitle = null,
        ?string $orderDateFrom = null,
        ?string $orderDateTo = null,
        ?string $buyer = null,
        ?string $seller = null
    ) : Collection
    {
        try {
            return OrderItemFinishRequest::query()
                ->with([
                    'item.vybe' => function ($query) {
                        $query->select([
                            'id',
                            'title'
                        ]);
                    },
                    'buyer' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'seller' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->where('item_id', '=', $orderItemId);
                })
                ->when($fromRequestDatetime, function ($query) use ($fromRequestDatetime) {
                    $query->where('from_request_datetime', '>=', Carbon::parse($fromRequestDatetime));
                })
                ->when($toRequestDatetime, function ($query) use ($toRequestDatetime) {
                    $query->where('to_request_datetime', '<=', Carbon::parse($toRequestDatetime));
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->whereHas('item.vybe', function($query) use ($vybeTitle) {
                        $query->where('title', 'LIKE', '%'. $vybeTitle . '%');
                    });
                })
                ->when($orderDateFrom, function ($query) use ($orderDateFrom) {
                    $query->where('from_request_datetime', '>=', Carbon::parse($orderDateFrom));
                })
                ->when($orderDateTo, function ($query) use ($orderDateTo) {
                    $query->where('to_request_datetime', '<=', Carbon::parse($orderDateTo));
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('seller', function($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller . '%');
                    });
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItemRequestStatusListItem $orderItemRequestStatusListItem
     *
     * @return int
     *
     * @throws DatabaseException
     */
    public function getRequestStatusCount(
        OrderItemRequestStatusListItem $orderItemRequestStatusListItem
    ) : int
    {
        try {
            return OrderItemFinishRequest::query()
                ->where('request_status_id', '=', $orderItemRequestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ids
     * @param OrderItemRequestStatusListItem $orderItemRequestStatusListItem
     *
     * @return int
     *
     * @throws DatabaseException
     */
    public function getRequestStatusCountByIds(
        array $ids,
        OrderItemRequestStatusListItem $orderItemRequestStatusListItem
    ) : int
    {
        try {
            return OrderItemFinishRequest::query()
                ->whereIn('_id', $ids)
                ->where('request_status_id', '=', $orderItemRequestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItemStatusListItem $orderItemStatusListItem
     *
     * @return int
     *
     * @throws DatabaseException
     */
    public function getToOrderItemStatusCount(
        OrderItemStatusListItem $orderItemStatusListItem
    ) : int
    {
        try {
            return OrderItemFinishRequest::query()
                ->where('to_order_item_status_id', '=', $orderItemStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ids
     * @param OrderItemStatusListItem $orderItemStatusListItem
     *
     * @return int
     *
     * @throws DatabaseException
     */
    public function getToOrderItemStatusCountByIds(
        array $ids,
        OrderItemStatusListItem $orderItemStatusListItem
    ) : int
    {
        try {
            return OrderItemFinishRequest::query()
                ->whereIn('_id', $ids)
                ->where('to_order_item_status_id', '=', $orderItemStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function store(
        OrderItem $orderItem,
        User $buyer,
        User $seller,
        User $opening,
        OrderItemRequestInitiatorListItem $orderItemRequestInitiatorListItem,
        OrderItemStatusListItem $fromOrderItemStatusListItem,
        ?OrderItemStatusListItem $toOrderItemStatusListItem = null,
        ?OrderItemRequestActionListItem $orderItemRequestActionListItem = null,
        ?string $fromRequestDatetime = null,
        ?string $toRequestDatetime = null
    ) : ?OrderItemFinishRequest
    {
        try {
            return OrderItemFinishRequest::query()->create([
                'item_id'                   => $orderItem->id,
                'buyer_id'                  => $buyer->id,
                'seller_id'                 => $seller->id,
                'opening_id'                => $opening->id,
                'initiator_id'              => $orderItemRequestInitiatorListItem->id,
                'from_order_item_status_id' => $fromOrderItemStatusListItem->id,
                'to_order_item_status_id'   => $toOrderItemStatusListItem?->id,
                'action_id'                 => $orderItemRequestActionListItem?->id,
                'request_status_id'         => OrderItemRequestStatusList::getActive()->id,
                'from_request_datetime'     => $fromRequestDatetime ?
                    Carbon::parse($fromRequestDatetime)->format('Y-m-d H:i:s') :
                    Carbon::now()->format('Y-m-d H:i:s'),
                'to_request_datetime'       => $toRequestDatetime ?
                    Carbon::parse($toRequestDatetime)->format('Y-m-d H:i:s') :
                    null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
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
    ) : OrderItemFinishRequest
    {
        try {
            $orderItemFinishRequest->update([
                'item_id'                   => $orderItem ? $orderItem->id : $orderItemFinishRequest->item_id,
                'buyer_id'                  => $buyer ? $buyer->id : $orderItemFinishRequest->buyer_id,
                'seller_id'                 => $seller ? $seller->id : $orderItemFinishRequest->seller_id,
                'opening_id'                => $opening ? $opening->id : $orderItemFinishRequest->opening_id,
                'initiator_id'              => $orderItemRequestInitiatorListItem ? $orderItemRequestInitiatorListItem->id : $orderItemFinishRequest->initiator_id,
                'from_order_item_status_id' => $fromOrderItemStatusListItem ? $fromOrderItemStatusListItem->id : $orderItemFinishRequest->from_order_item_status_id,
                'to_order_item_status_id'   => $toOrderItemStatusListItem ? $toOrderItemStatusListItem->id : $orderItemFinishRequest->to_order_item_status_id,
                'action_id'                 => $orderItemRequestActionListItem ? $orderItemRequestActionListItem->id : $orderItemFinishRequest->action_id,
                'request_status_id'         => $orderItemRequestStatusListItem ? $orderItemRequestStatusListItem->id : $orderItemFinishRequest->request_status_id,
                'from_request_datetime'     => $fromRequestDatetime ? Carbon::parse($fromRequestDatetime)->format('Y-m-d H:i:s') : $orderItemFinishRequest->from_request_datetime,
                'to_request_datetime'       => $toRequestDatetime ? Carbon::parse($toRequestDatetime)->format('Y-m-d H:i:s') : $orderItemFinishRequest->to_request_datetime
            ]);

            return $orderItemFinishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     * @param User|null $closing
     * @param OrderItemStatusListItem|null $toOrderItemStatusListItem
     * @param OrderItemRequestActionListItem|null $orderItemRequestActionListItem
     *
     * @return OrderItemFinishRequest
     *
     * @throws DatabaseException
     */
    public function updateWhenClose(
        OrderItemFinishRequest $orderItemFinishRequest,
        ?User $closing,
        ?OrderItemStatusListItem $toOrderItemStatusListItem,
        ?OrderItemRequestActionListItem $orderItemRequestActionListItem
    ) : OrderItemFinishRequest
    {
        try {
            $orderItemFinishRequest->update([
                'closing'                 => $closing ?: $orderItemFinishRequest->closing_id,
                'to_order_item_status_id' => $toOrderItemStatusListItem ? $toOrderItemStatusListItem->id : $orderItemFinishRequest->to_order_item_status_id,
                'action_id'               => $orderItemRequestActionListItem ? $orderItemRequestActionListItem->id : $orderItemFinishRequest->action_id,
                'request_status_id'       => OrderItemRequestStatusList::getFinished()->id,
                'to_request_datetime'     => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            return $orderItemFinishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemPendingRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     * @param OrderItemRequestActionListItem $orderItemRequestActionListItem
     *
     * @return OrderItemFinishRequest|null
     *
     * @throws DatabaseException
     */
    public function updateAction(
        OrderItemFinishRequest $orderItemFinishRequest,
        OrderItemRequestActionListItem $orderItemRequestActionListItem
    ) : ?OrderItemFinishRequest
    {
        try {
            $orderItemFinishRequest->update([
                'action_id' => $orderItemRequestActionListItem->id
            ]);

            return $orderItemFinishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItemFinishRequest $orderItemFinishRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        OrderItemFinishRequest $orderItemFinishRequest
    ) : bool
    {
        try {
            return $orderItemFinishRequest->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItemFinishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}