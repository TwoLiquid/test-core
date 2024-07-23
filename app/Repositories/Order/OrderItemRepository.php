<?php

namespace App\Repositories\Order;

use App\Exceptions\DatabaseException;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Lists\Order\Item\Payment\Status\OrderItemPaymentStatusListItem;
use App\Lists\Order\Item\Purchase\SortBy\OrderItemPurchaseSortByListItem;
use App\Lists\Order\Item\Status\OrderItemStatusList;
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
use App\Repositories\BaseRepository;
use App\Repositories\Order\Interfaces\OrderItemRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class OrderItemRepository
 *
 * @package App\Repositories\Order
 */
class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    /**
     * OrderItemRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.orderItem.cacheTime');
        $this->perPage = config('repositories.orderItem.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return OrderItem|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?OrderItem
    {
        try {
            return OrderItem::query()
                ->with([
                    'order'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return OrderItem|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?int $id
    ) : ?OrderItem
    {
        try {
            return OrderItem::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    },
                    'seller' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'appearanceCase' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'unit_id',
                            'appearance_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'activity_id',
                                    'status_id',
                                    'type_id',
                                    'title'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username'
                                        ]);
                                    },
                                    'activity' => function ($query) {
                                        $query->select([
                                            'id',
                                            'code',
                                            'name'
                                        ]);
                                    }
                                ]);
                            },
                            'unit' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
                                ]);
                            }
                        ]);
                    },
                    'timeslot' => function ($query) {
                        $query->select([
                            'id',
                            'datetime_from',
                            'datetime_to'
                        ]);
                    },
                    'invoices' => function ($query) {
                        $query->select([
                            'id',
                            'type_id'
                        ]);
                    },
                    'pendingRequests',
                    'rescheduleRequests',
                    'finishRequests'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
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
            return Cache::remember('orderItems.all.count', $this->cacheTime,
                function () {
                    return Order::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
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
            return OrderItem::query()
                ->with([
                    'order'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
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
            return OrderItem::query()
                ->with([
                    'order'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Order $order
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByOrder(
        Order $order
    ) : Collection
    {
        try {
            return $order->items()
                ->with([
                    'order'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?string $vybeTitle = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return OrderItem::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    },
                    'appearanceCase' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'appearance_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'status_id',
                                    'type_id',
                                    'title'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('order.buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTitle) {
                        $query->where('title', 'LIKE', '%'. $vybeTitle .'%');
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereIn('status_id', $orderItemStatusesIds);
                })
                ->when($orderItemPaymentStatusesIds, function ($query) use ($orderItemPaymentStatusesIds) {
                    $query->whereIn('payment_status_id', $orderItemPaymentStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.buyer_id', '=', 'users.id')
                                ->whereColumn('order_items.order_id', 'orders.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('order_items.seller_id', '=', 'users.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_title') {
                        $query->orderBy(
                            Vybe::query()->select('title')
                                ->whereColumn('order_items.vybe_id', 'vybes.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->whereColumn('order_items.vybe_id', 'vybes.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'handling_fee') {
                        $query->orderBy('handling_fee', $sortOrder);
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy('payment_status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllPaginatedFiltered(
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?string $vybeTitle = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return OrderItem::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    },
                    'appearanceCase' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'appearance_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'status_id',
                                    'type_id',
                                    'title'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', $dateFrom);
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', $dateTo);
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('order.buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTitle) {
                        $query->where('title', 'LIKE', '%'. $vybeTitle .'%');
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereIn('status_id', $orderItemStatusesIds);
                })
                ->when($orderItemPaymentStatusesIds, function ($query) use ($orderItemPaymentStatusesIds) {
                    $query->whereIn('payment_status_id', $orderItemPaymentStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.buyer_id', '=', 'users.id')
                                ->whereColumn('order_items.order_id', 'orders.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('order_items.seller_id', '=', 'users.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_title') {
                        $query->orderBy(
                            Vybe::query()->select('title')
                                ->whereColumn('order_items.vybe_id', 'vybes.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->whereColumn('order_items.vybe_id', 'vybes.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'handling_fee') {
                        $query->orderBy('handling_fee', $sortOrder);
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy('payment_status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param string|null $seller
     * @param string|null $vybeTitle
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForAdminLabels(
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?string $vybeTitle = null
    ) : Collection
    {
        try {
            return OrderItem::query()
                ->select([
                    'id',
                    'order_id',
                    'vybe_id'
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('order.buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTitle) {
                        $query->where('title', 'LIKE', '%'. $vybeTitle .'%');
                    });
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllForBuyerFiltered(
        User $buyer,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $seller = null,
        ?int $vybeVersion = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return OrderItem::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    },
                    'appearanceCase' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'appearance_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'status_id',
                                    'type_id',
                                    'title'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeVersion) {
                        $query->where('version', '=', $vybeVersion);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereIn('status_id', $orderItemStatusesIds);
                })
                ->when($orderItemPaymentStatusesIds, function ($query) use ($orderItemPaymentStatusesIds) {
                    $query->whereIn('payment_status_id', $orderItemPaymentStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('order_items.seller_id', '=', 'users.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'vybe_version') {
                        $query->orderBy(
                            Vybe::query()->select('version')
                                ->whereColumn('order_items.vybe_id', 'vybes.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->whereColumn('order_items.vybe_id', 'vybes.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy('payment_status_id', $sortOrder);
                    }
                })
                ->whereHas('order', function($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllForBuyerPaginatedFiltered(
        User $buyer,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $seller = null,
        ?int $vybeVersion = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return OrderItem::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    },
                    'appearanceCase' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'appearance_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'status_id',
                                    'type_id',
                                    'title'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeVersion) {
                        $query->where('version', '=', $vybeVersion);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereIn('status_id', $orderItemStatusesIds);
                })
                ->when($orderItemPaymentStatusesIds, function ($query) use ($orderItemPaymentStatusesIds) {
                    $query->whereIn('payment_status_id', $orderItemPaymentStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('order_items.seller_id', '=', 'users.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'vybe_version') {
                        $query->orderBy(
                            Vybe::query()->select('version')
                                ->whereColumn('order_items.vybe_id', 'vybes.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->whereColumn('order_items.vybe_id', 'vybes.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy('payment_status_id', $sortOrder);
                    }
                })
                ->whereHas('order', function($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $buyer
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $seller
     * @param int|null $vybeVersion
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForBuyerFilteredForAdminLabels(
        User $buyer,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $seller = null,
        ?int $vybeVersion = null
    ) : Collection
    {
        try {
            return OrderItem::query()
                ->select([
                    'id',
                    'vybe_id'
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeVersion) {
                        $query->where('version', '=', $vybeVersion);
                    });
                })
                ->whereHas('order', function($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllForSellerFiltered(
        User $seller,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?int $vybeVersion = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return OrderItem::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    },
                    'appearanceCase' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'appearance_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'status_id',
                                    'type_id',
                                    'title'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'invoices' => function ($query) {
                        $query->where('type_id', '=', InvoiceTypeList::getSeller()->id);
                    }
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('order.buyer', function ($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer .'%');
                    });
                })
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeVersion) {
                        $query->where('version', '=', $vybeVersion);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereIn('status_id', $orderItemStatusesIds);
                })
                ->when($orderItemPaymentStatusesIds, function ($query) use ($orderItemPaymentStatusesIds) {
                    $query->whereIn('payment_status_id', $orderItemPaymentStatusesIds);
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereHas('order.invoices', function ($query) use ($invoiceStatusesIds) {
                        $query->whereIn('status_id', '=', $invoiceStatusesIds)
                            ->where('type_id', '=', InvoiceTypeList::getSeller()->id);
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.buyer_id', '=', 'users.id')
                                ->whereColumn('order_items.order_id', 'orders.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'vybe_version') {
                        $query->orderBy(
                            Vybe::query()->select('version')
                                ->whereColumn('order_items.vybe_id', 'vybes.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->whereColumn('order_items.vybe_id', 'vybes.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy('payment_status_id', $sortOrder);
                    }

                    if ($sortBy == 'invoice_status') {
                        $query->orderBy(
                            OrderInvoice::query()->select('status_id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->where('order_invoices.type_id', '=', InvoiceTypeList::getSeller()->id)
                                ->whereColumn('order_items.id', '=', 'invoice_order_item.item_id'),
                            $sortOrder
                        );
                    }
                })
                ->where('seller_id','=', $seller->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllForSellerPaginatedFiltered(
        User $seller,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?int $vybeVersion = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return OrderItem::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    },
                    'appearanceCase' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'appearance_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'status_id',
                                    'type_id',
                                    'title'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'invoices' => function ($query) {
                        $query->where('type_id', '=', InvoiceTypeList::getSeller()->id);
                    }
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('order.buyer', function ($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer .'%');
                    });
                })
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeVersion) {
                        $query->where('version', '=', $vybeVersion);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereIn('status_id', $orderItemStatusesIds);
                })
                ->when($orderItemPaymentStatusesIds, function ($query) use ($orderItemPaymentStatusesIds) {
                    $query->whereIn('payment_status_id', $orderItemPaymentStatusesIds);
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereHas('order.invoices', function ($query) use ($invoiceStatusesIds) {
                        $query->whereIn('status_id', '=', $invoiceStatusesIds)
                            ->where('type_id', '=', InvoiceTypeList::getSeller()->id);
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.buyer_id', '=', 'users.id')
                                ->whereColumn('order_items.order_id', 'orders.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'vybe_version') {
                        $query->orderBy(
                            Vybe::query()->select('version')
                                ->whereColumn('order_items.vybe_id', 'vybes.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->whereColumn('order_items.vybe_id', 'vybes.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy('payment_status_id', $sortOrder);
                    }

                    if ($sortBy == 'invoice_status') {
                        $query->orderBy(
                            OrderInvoice::query()->select('status_id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->where('order_invoices.type_id', '=', InvoiceTypeList::getSeller()->id)
                                ->whereColumn('order_items.id', '=', 'invoice_order_item.item_id'),
                            $sortOrder
                        );
                    }
                })
                ->where('seller_id','=', $seller->id)
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $seller
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param int|null $vybeVersion
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForSellerFilteredForAdminLabels(
        User $seller,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?int $vybeVersion = null
    ) : Collection
    {
        try {
            return OrderItem::query()
                ->select([
                    'id',
                    'order_id',
                    'vybe_id',
                ])
                ->with([
                    'invoices' => function ($query) {
                        $query->where('type_id', '=', InvoiceTypeList::getSeller()->id);
                    }
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('order.buyer', function ($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer .'%');
                    });
                })
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeVersion) {
                        $query->where('version', '=', $vybeVersion);
                    });
                })
                ->where('seller_id','=', $seller->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllSalesFiltered(
        User $seller,
        ?VybeAppearanceListItem $vybeAppearanceListItem = null,
        ?VybeTypeListItem $vybeTypeListItem = null,
        ?Activity $activity = null,
        ?OrderItemStatusListItem $orderItemStatusListItem = null,
        ?OrderItemPurchaseSortByListItem $orderItemPurchaseSortByListItem = null,
        ?string $vybeTitle = null,
        ?string $username = null,
        ?string $datetimeFrom = null,
        ?string $datetimeTo = null,
        ?int $amountFrom = null,
        ?int $amountTo = null,
        ?int $quantity = null,
        ?bool $onlyOpen = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return OrderItem::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    },
                    'vybe' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'activity_id',
                            'status_id',
                            'type_id',
                            'title',
                            'user_count'
                        ])->with([
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'seller' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'appearanceCase' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'unit_id',
                            'appearance_id'
                        ])->with([
                            'unit'
                        ]);
                    },
                    'timeslot' => function ($query) {
                        $query->with([
                            'users' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ])->withCount([
                            'users'
                        ]);
                    }
                ])
                ->when($vybeAppearanceListItem, function ($query) use ($vybeAppearanceListItem) {
                    $query->whereHas('appearanceCase', function ($query) use ($vybeAppearanceListItem) {
                        $query->where('appearance_id', '=', $vybeAppearanceListItem->id);
                    });
                })
                ->when($vybeTypeListItem, function ($query) use ($vybeTypeListItem) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTypeListItem) {
                        $query->where('type_id', '=', $vybeTypeListItem->id);
                    });
                })
                ->when($activity, function ($query) use ($activity) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($activity) {
                        $query->where('activity_id', '=', $activity->id);
                    });
                })
                ->when($orderItemStatusListItem, function ($query) use ($orderItemStatusListItem) {
                    $query->where('status_id', '=', $orderItemStatusListItem->id);
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTitle) {
                        $query->where('title', '=', $vybeTitle);
                    });
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('order.buyer', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username .'%');
                    });
                })
                ->when($datetimeFrom, function ($query) use ($datetimeFrom) {
                    $query->whereHas('timeslot', function ($query) use ($datetimeFrom) {
                        $query->where('datetime_from', '>=', $datetimeFrom);
                    });
                })
                ->when($datetimeTo, function ($query) use ($datetimeTo) {
                    $query->whereHas('timeslot', function ($query) use ($datetimeTo) {
                        $query->where('datetime_to', '<=', $datetimeTo);
                    });
                })
                ->when($amountFrom, function ($query) use ($amountFrom) {
                    $query->where('amount', '>=', $amountFrom);
                })
                ->when($amountTo, function ($query) use ($amountTo) {
                    $query->where('amount', '<=', $amountTo);
                })
                ->when($quantity, function ($query) use ($quantity) {
//                    $query->where('quantity', '=', $quantity);
                })
                ->when($onlyOpen, function ($query) use ($onlyOpen) {
                    $query->whereIn('status_id', [
                        OrderItemStatusList::getPending()->id,
                        OrderItemStatusList::getReschedule()->id,
                        OrderItemStatusList::getInProcess()->id,
                        OrderItemStatusList::getFinishRequest()->id
                    ]);
                })
                ->when($orderItemPurchaseSortByListItem && $sortOrder, function ($query) use ($orderItemPurchaseSortByListItem, $sortOrder) {
                    if ($orderItemPurchaseSortByListItem->isEarliestPurchasesFirst() ||
                        $orderItemPurchaseSortByListItem->isLatestPurchasesFirst()
                    ) {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($orderItemPurchaseSortByListItem->isEarliestStartingVybesFirst() ||
                        $orderItemPurchaseSortByListItem->isLatestStartingVybesFirst()
                    ) {
                        $query->orderBy(
                            Timeslot::query()->select('datetime_from')
                                ->whereColumn('order_items.timeslot_id', 'timeslots.id'),
                            $sortOrder
                        );
                    }
                })
                ->where('seller_id','=', $seller->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
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
    ) : LengthAwarePaginator
    {
        try {
            return OrderItem::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username',
                                    'avatar_id'
                                ]);
                            }
                        ]);
                    },
                    'vybe' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'activity_id',
                            'status_id',
                            'type_id',
                            'title',
                            'user_count'
                        ])->with([
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'seller' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username',
                            'avatar_id'
                        ]);
                    },
                    'appearanceCase' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'unit_id',
                            'appearance_id'
                        ])->with([
                            'unit'
                        ]);
                    },
                    'timeslot' => function ($query) {
                        $query->with([
                            'users' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username',
                                    'avatar_id'
                                ]);
                            }
                        ])->withCount([
                            'users'
                        ]);
                    }
                ])
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTitle) {
                        $query->where('title', '=', $vybeTitle);
                    });
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('order.buyer', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username .'%');
                    });
                })
                ->when($datetimeFrom, function ($query) use ($datetimeFrom) {
                    $query->whereHas('timeslot', function ($query) use ($datetimeFrom) {
                        $query->where('datetime_from', '>=', $datetimeFrom);
                    });
                })
                ->when($datetimeTo, function ($query) use ($datetimeTo) {
                    $query->whereHas('timeslot', function ($query) use ($datetimeTo) {
                        $query->where('datetime_to', '<=', $datetimeTo);
                    });
                })
                ->when($amountFrom, function ($query) use ($amountFrom) {
                    $query->where('amount', '>=', $amountFrom);
                })
                ->when($amountTo, function ($query) use ($amountTo) {
                    $query->where('amount', '<=', $amountTo);
                })
                ->when($quantity, function ($query) use ($quantity) {
//                    $query->where('quantity', '=', $quantity);
                })
                ->when($onlyOpen, function ($query) use ($onlyOpen) {
                    $query->whereIn('status_id', [
                        OrderItemStatusList::getPending()->id,
                        OrderItemStatusList::getReschedule()->id,
                        OrderItemStatusList::getInProcess()->id,
                        OrderItemStatusList::getFinishRequest()->id
                    ]);
                })
                ->when($orderItemPurchaseSortByListItem && $sortOrder, function ($query) use ($orderItemPurchaseSortByListItem, $sortOrder) {
                    if ($orderItemPurchaseSortByListItem->isEarliestPurchasesFirst() ||
                        $orderItemPurchaseSortByListItem->isLatestPurchasesFirst()
                    ) {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($orderItemPurchaseSortByListItem->isEarliestStartingVybesFirst() ||
                        $orderItemPurchaseSortByListItem->isLatestStartingVybesFirst()
                    ) {
                        $query->orderBy(
                            Timeslot::query()->select('datetime_from')
                                ->whereColumn('order_items.timeslot_id', 'timeslots.id'),
                            $sortOrder
                        );
                    }
                })
                ->where('seller_id','=', $seller->id)
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
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
    ) : Collection
    {
        try {
            return OrderItem::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    },
                    'vybe' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'activity_id',
                            'status_id',
                            'type_id',
                            'title',
                            'user_count'
                        ])->with([
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'seller' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'appearanceCase' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'unit_id',
                            'appearance_id'
                        ])->with([
                            'unit'
                        ]);
                    },
                    'timeslot' => function ($query) {
                        $query->with([
                            'users' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ])->withCount([
                            'users'
                        ]);
                    }
                ])
                ->when($vybeAppearanceListItem, function ($query) use ($vybeAppearanceListItem) {
                    $query->whereHas('appearanceCase', function ($query) use ($vybeAppearanceListItem) {
                        $query->where('appearance_id', '=', $vybeAppearanceListItem->id);
                    });
                })
                ->when($vybeTypeListItem, function ($query) use ($vybeTypeListItem) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTypeListItem) {
                        $query->where('type_id', '=', $vybeTypeListItem->id);
                    });
                })
                ->when($activity, function ($query) use ($activity) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($activity) {
                        $query->where('activity_id', '=', $activity->id);
                    });
                })
                ->when($orderItemStatusListItem, function ($query) use ($orderItemStatusListItem) {
                    $query->where('status_id', '=', $orderItemStatusListItem->id);
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTitle) {
                        $query->where('title', 'LIKE', '%' . $vybeTitle . '%');
                    });
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('order.buyer', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%' . $username . '%');
                    });
                })
                ->when($datetimeFrom, function ($query) use ($datetimeFrom) {
                    $query->whereHas('timeslot', function ($query) use ($datetimeFrom) {
                        $query->where('datetime_from', '>=', $datetimeFrom);
                    });
                })
                ->when($datetimeTo, function ($query) use ($datetimeTo) {
                    $query->whereHas('timeslot', function ($query) use ($datetimeTo) {
                        $query->where('datetime_to', '<=', $datetimeTo);
                    });
                })
                ->when($amountFrom, function ($query) use ($amountFrom) {
                    $query->where('amount', '>=', $amountFrom);
                })
                ->when($amountTo, function ($query) use ($amountTo) {
                    $query->where('amount', '<=', $amountTo);
                })
                ->when($quantity, function ($query) use ($quantity) {
//                    $query->where('quantity', '=', $quantity);
                })
                ->when($onlyOpen, function ($query) use ($onlyOpen) {
                    $query->whereIn('status_id', [
                        OrderItemStatusList::getPending()->id,
                        OrderItemStatusList::getReschedule()->id,
                        OrderItemStatusList::getInProcess()->id,
                        OrderItemStatusList::getFinishRequest()->id
                    ]);
                })
                ->when($orderItemPurchaseSortByListItem && $sortOrder, function ($query) use ($orderItemPurchaseSortByListItem, $sortOrder) {
                    if ($orderItemPurchaseSortByListItem->isEarliestPurchasesFirst() ||
                        $orderItemPurchaseSortByListItem->isLatestPurchasesFirst()
                    ) {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($orderItemPurchaseSortByListItem->isEarliestStartingVybesFirst() ||
                        $orderItemPurchaseSortByListItem->isLatestStartingVybesFirst()
                    ) {
                        $query->orderBy(
                            Timeslot::query()->select('datetime_from')
                                ->whereColumn('order_items.timeslot_id', 'timeslots.id'),
                            $sortOrder
                        );
                    }
                })
                ->whereHas('order.buyer', function ($query) use ($buyer) {
                    $query->where('id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
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
    ) : LengthAwarePaginator
    {
        try {
            return OrderItem::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username',
                                    'avatar_id'
                                ]);
                            }
                        ]);
                    },
                    'vybe' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'activity_id',
                            'status_id',
                            'type_id',
                            'title',
                            'user_count'
                        ])->with([
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'seller' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username',
                            'avatar_id'
                        ]);
                    },
                    'appearanceCase' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'unit_id',
                            'appearance_id'
                        ])->with([
                            'unit'
                        ]);
                    },
                    'timeslot' => function ($query) {
                        $query->with([
                            'users' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username',
                                    'avatar_id'
                                ]);
                            }
                        ])->withCount([
                            'users'
                        ]);
                    }
                ])
                ->when($vybeAppearanceListItem, function ($query) use ($vybeAppearanceListItem) {
                    $query->whereHas('appearanceCase', function ($query) use ($vybeAppearanceListItem) {
                        $query->where('appearance_id', '=', $vybeAppearanceListItem->id);
                    });
                })
                ->when($vybeTypeListItem, function ($query) use ($vybeTypeListItem) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTypeListItem) {
                        $query->where('type_id', '=', $vybeTypeListItem->id);
                    });
                })
                ->when($activity, function ($query) use ($activity) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($activity) {
                        $query->where('activity_id', '=', $activity->id);
                    });
                })
                ->when($orderItemStatusListItem, function ($query) use ($orderItemStatusListItem) {
                    $query->where('status_id', '=', $orderItemStatusListItem->id);
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->whereHas('appearanceCase.vybe', function ($query) use ($vybeTitle) {
                        $query->where('title', 'LIKE', '%' . $vybeTitle . '%');
                    });
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('order.buyer', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%' . $username . '%');
                    });
                })
                ->when($datetimeFrom, function ($query) use ($datetimeFrom) {
                    $query->whereHas('timeslot', function ($query) use ($datetimeFrom) {
                        $query->where('datetime_from', '>=', $datetimeFrom);
                    });
                })
                ->when($datetimeTo, function ($query) use ($datetimeTo) {
                    $query->whereHas('timeslot', function ($query) use ($datetimeTo) {
                        $query->where('datetime_to', '<=', $datetimeTo);
                    });
                })
                ->when($amountFrom, function ($query) use ($amountFrom) {
                    $query->where('amount', '>=', $amountFrom);
                })
                ->when($amountTo, function ($query) use ($amountTo) {
                    $query->where('amount', '<=', $amountTo);
                })
                ->when($quantity, function ($query) use ($quantity) {
//                    $query->where('quantity', '=', $quantity);
                })
                ->when($onlyOpen, function ($query) use ($onlyOpen) {
                    $query->whereIn('status_id', [
                        OrderItemStatusList::getPending()->id,
                        OrderItemStatusList::getReschedule()->id,
                        OrderItemStatusList::getInProcess()->id,
                        OrderItemStatusList::getFinishRequest()->id
                    ]);
                })
                ->when($orderItemPurchaseSortByListItem && $sortOrder, function ($query) use ($orderItemPurchaseSortByListItem, $sortOrder) {
                    if ($orderItemPurchaseSortByListItem->isEarliestPurchasesFirst() ||
                        $orderItemPurchaseSortByListItem->isLatestPurchasesFirst()
                    ) {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($orderItemPurchaseSortByListItem->isEarliestStartingVybesFirst() ||
                        $orderItemPurchaseSortByListItem->isLatestStartingVybesFirst()
                    ) {
                        $query->orderBy(
                            Timeslot::query()->select('datetime_from')
                                ->whereColumn('order_items.timeslot_id', 'timeslots.id'),
                            $sortOrder
                        );
                    }
                })
                ->whereHas('order.buyer', function ($query) use ($buyer) {
                    $query->where('id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $orderItemsIds
     *
     * @return OrderItem
     *
     * @throws DatabaseException
     */
    public function getStatusesByIdsCount(
        array $orderItemsIds
    ) : OrderItem
    {
        try {
            return OrderItem::query()
                ->whereIn('id', $orderItemsIds)
                ->selectRaw('sum(case when status_id = 1 then 1 else 0 end) as reserved')
                ->selectRaw('sum(case when status_id = 2 then 1 else 0 end) as pending')
                ->selectRaw('sum(case when status_id = 3 then 1 else 0 end) as in_process')
                ->selectRaw('sum(case when status_id = 4 then 1 else 0 end) as reschedule_request')
                ->selectRaw('sum(case when status_id = 5 then 1 else 0 end) as finish_request')
                ->selectRaw('sum(case when status_id = 6 then 1 else 0 end) as canceled')
                ->selectRaw('sum(case when status_id = 7 then 1 else 0 end) as canceled_dispute')
                ->selectRaw('sum(case when status_id = 8 then 1 else 0 end) as disputed')
                ->selectRaw('sum(case when status_id = 9 then 1 else 0 end) as finished')
                ->selectRaw('sum(case when status_id = 10 then 1 else 0 end) as finished_dispute')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $orderItemsIds
     *
     * @return OrderItem
     *
     * @throws DatabaseException
     */
    public function getPaymentStatusesByIdsCount(
        array $orderItemsIds
    ) : OrderItem
    {
        try {
            return OrderItem::query()
                ->whereIn('id', $orderItemsIds)
                ->selectRaw('sum(case when payment_status_id = 1 then 1 else 0 end) as unpaid')
                ->selectRaw('sum(case when payment_status_id = 2 then 1 else 0 end) as paid')
                ->selectRaw('sum(case when payment_status_id = 3 then 1 else 0 end) as canceled')
                ->selectRaw('sum(case when payment_status_id = 4 then 1 else 0 end) as refunded')
                ->selectRaw('sum(case when payment_status_id = 5 then 1 else 0 end) as paid_partial_refund')
                ->selectRaw('sum(case when payment_status_id = 6 then 1 else 0 end) as chargeback')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForSellerStatistic(
        User $user
    ) : Collection
    {
        try {
            return OrderItem::query()
                ->select([
                    'id',
                    'seller_id',
                    'status_id',
                    'payment_status_id',
                    'expired_at',
                    'accepted_at',
                    'finished_at',
                    'created_at'
                ])
                ->where('seller_id', '=', $user->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
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
        float $amountTax = 0,
        float $handlingFee = 0
    ) : ?OrderItem
    {
        try {
            return OrderItem::query()->create([
                'order_id'           => $order->id,
                'vybe_id'            => $vybe->id,
                'seller_id'          => $seller->id,
                'appearance_case_id' => $appearanceCase->id,
                'timeslot_id'        => $timeslot->id,
                'status_id'          => $orderItemStatusListItem->id,
                'payment_status_id'  => $orderItemPaymentStatusListItem->id,
                'vybe_version'       => $vybeVersion,
                'price'              => $price,
                'quantity'           => $quantity,
                'amount_earned'      => $amountEarned,
                'amount_total'       => $amountTotal,
                'amount_tax'         => $amountTax,
                'handling_fee'       => $handlingFee,
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
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
    ) : OrderItem
    {
        try {
            $orderItem->update([
                'order_id'           => $order ? $order->id : $orderItem->order_id,
                'seller_id'          => $seller ? $seller->id : $orderItem->seller_id,
                'appearance_case_id' => $appearanceCase ? $appearanceCase->id : $orderItem->appearance_case_id,
                'timeslot_id'        => $timeslot ? $timeslot->id : $orderItem->timeslot_id,
                'status_id'          => $orderItemStatusListItem ? $orderItemStatusListItem->id : $orderItem->status_id,
                'payment_status_id'  => $orderItemPaymentStatusListItem ? $orderItemPaymentStatusListItem->id : $orderItem->payment_status_id,
                'vybe_version'       => $vybeVersion ?: $orderItem->vybe_version,
                'price'              => $price ?: $orderItem->price,
                'quantity'           => $quantity ?: $orderItem->quantity,
                'amount_earned'      => $amountEarned ?: $orderItem->amount_earned,
                'amount_total'       => $amountTotal ?: $orderItem->amount_total,
                'amount_tax'         => $amountTax ?: $orderItem->amount_tax,
                'handling_fee'       => $handlingFee ?: $orderItem->handling_fee
            ]);

            return $orderItem;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param Timeslot $timeslot
     *
     * @return OrderItem
     *
     * @throws DatabaseException
     */
    public function updateTimeslot(
        OrderItem $orderItem,
        Timeslot $timeslot
    ) : OrderItem
    {
        try {
            $orderItem->update([
                'timeslot_id' => $timeslot->id
            ]);

            return $orderItem;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param OrderItemStatusListItem $orderItemStatusListItem
     *
     * @return OrderItem
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        OrderItem $orderItem,
        OrderItemStatusListItem $orderItemStatusListItem
    ) : OrderItem
    {
        try {
            $orderItem->update([
                'status_id' => $orderItemStatusListItem->id
            ]);

            return $orderItem;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param OrderItemStatusListItem $orderItemStatusListItem
     *
     * @return OrderItem
     *
     * @throws DatabaseException
     */
    public function updatePreviousStatus(
        OrderItem $orderItem,
        OrderItemStatusListItem $orderItemStatusListItem
    ) : OrderItem
    {
        try {
            $orderItem->update([
                'previous_status_id' => $orderItemStatusListItem->id
            ]);

            return $orderItem;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem
     *
     * @return OrderItem
     *
     * @throws DatabaseException
     */
    public function updatePaymentStatus(
        OrderItem $orderItem,
        OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem
    ) : OrderItem
    {
        try {
            $orderItem->update([
                'payment_status_id' => $orderItemPaymentStatusListItem->id
            ]);

            return $orderItem;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param OrderItemStatusListItem $orderItemStatusListItem
     * @param OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem
     *
     * @return OrderItem
     *
     * @throws DatabaseException
     */
    public function updateStatuses(
        OrderItem $orderItem,
        OrderItemStatusListItem $orderItemStatusListItem,
        OrderItemPaymentStatusListItem $orderItemPaymentStatusListItem
    ) : OrderItem
    {
        try {
            $orderItem->update([
                'status_id'         => $orderItemStatusListItem->id,
                'payment_status_id' => $orderItemPaymentStatusListItem->id
            ]);

            return $orderItem;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param Carbon $expiredAt
     *
     * @return OrderItem
     *
     * @throws DatabaseException
     */
    public function updateExpiredAt(
        OrderItem $orderItem,
        Carbon $expiredAt
    ) : OrderItem
    {
        try {
            $orderItem->update([
                'expired_at' => $expiredAt->format('Y-m-d H:i:s')
            ]);

            return $orderItem;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param Carbon $acceptedAt
     *
     * @return OrderItem
     *
     * @throws DatabaseException
     */
    public function updateAcceptedAt(
        OrderItem $orderItem,
        Carbon $acceptedAt
    ) : OrderItem
    {
        try {
            $orderItem->update([
                'accepted_at' => $acceptedAt->format('Y-m-d H:i:s')
            ]);

            return $orderItem;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param Carbon $finishedAt
     *
     * @return OrderItem
     *
     * @throws DatabaseException
     */
    public function updateFinishedAt(
        OrderItem $orderItem,
        Carbon $finishedAt
    ) : OrderItem
    {
        try {
            $orderItem->update([
                'finished_at' => $finishedAt->format('Y-m-d H:i:s')
            ]);

            return $orderItem;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param OrderInvoice $orderInvoice
     *
     * @throws DatabaseException
     */
    public function attachInvoice(
        OrderItem $orderItem,
        OrderInvoice $orderInvoice
    ) : void
    {
        try {
            $orderItem->invoices()->sync([
                $orderInvoice->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param array $invoicesIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachInvoices(
        OrderItem $orderItem,
        array $invoicesIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $orderItem->invoices()->sync(
                $invoicesIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param OrderInvoice $orderInvoice
     *
     * @throws DatabaseException
     */
    public function detachInvoice(
        OrderItem $orderItem,
        OrderInvoice $orderInvoice
    ) : void
    {
        try {
            $orderItem->invoices()->detach([
                $orderInvoice->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param array $invoicesIds
     *
     * @throws DatabaseException
     */
    public function detachInvoices(
        OrderItem $orderItem,
        array $invoicesIds
    ) : void
    {
        try {
            $orderItem->invoices()->detach(
                $invoicesIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        OrderItem $orderItem
    ) : bool
    {
        try {
            return $orderItem->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderItem.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
