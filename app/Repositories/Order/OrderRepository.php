<?php

namespace App\Repositories\Order;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Order\Interfaces\OrderRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class OrderRepository
 *
 * @package App\Repositories\Order
 */
class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * OrderRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.order.cacheTime');
        $this->perPage = config('repositories.order.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Order|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Order
    {
        try {
            return Order::query()
                ->with([
                    'buyer',
                    'method',
                    'items'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return Order|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?int $id
    ) : ?Order
    {
        try {
            return Order::query()
                ->with([
                    'method' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    },
                    'buyer' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ])->with([
                            'balances'
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
                            'appearanceCase' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'appearance_id',
                                    'unit_id'
                                ])->with([
                                    'vybe' => function ($query) {
                                        $query->select([
                                            'id',
                                            'user_id',
                                            'activity_id',
                                            'type_id',
                                            'status_id',
                                            'title',
                                            'period_id',
                                            'type_id'
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
                                                    'name',
                                                    'category_id'
                                                ])
                                                    ->with([
                                                        'category' => function ($query) {
                                                            $query->select([
                                                                'id',
                                                                'name',
                                                                'code',
                                                                'parent_id'
                                                            ])
                                                                ->with([
                                                                    'parent' => function ($query) {
                                                                        $query->select([
                                                                            'id',
                                                                            'code',
                                                                            'name'
                                                                        ]);
                                                                    }
                                                                ]);
                                                        }
                                                    ]);
                                            }
                                        ]);
                                    },
                                    'unit' => function ($query) {
                                        $query->select([
                                            'id',
                                            'name'
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
                            }
                        ]);
                    },
                    'invoices' => function ($query) {
                        $query->select([
                            'id',
                            'order_id',
                            'type_id',
                            'status_id',
                            'created_at'
                        ])->with([
                            'items' => function ($query) {
                                $query->with([
                                    'appearanceCase' => function ($query) {
                                        $query->select([
                                            'id',
                                            'vybe_id'
                                        ])->with([
                                            'vybe' => function ($query) {
                                                $query->select([
                                                    'id',
                                                    'user_id'
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
                                ]);
                            }
                        ]);
                    }
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $seller
     * @param User $buyer
     *
     * @return Order|null
     *
     * @throws DatabaseException
     */
    public function findLastBySellerAndBuyer(
        User $seller,
        User $buyer
    ) : ?Order
    {
        try {
            return Order::query()
                ->where('buyer_id', $buyer->id)
                ->whereRelation('items', 'seller_id', '=', $seller->id)
                ->with([
                    'items' => function ($query) {
                        $query->with([
                            'timeslot' => function ($query) {
                                $query->select([
                                    'id',
                                    'datetime_from',
                                    'datetime_to'
                                ]);
                            }
                        ])->orderBy('id', 'desc');
                    }
                ])
                ->orderBy('id', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
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
            return Cache::remember('orders.all.count', $this->cacheTime,
                function () {
                    return Order::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ordersIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getOrdersByIds(
        array $ordersIds
    ) : Collection
    {
        try {
            return Order::query()
                ->select([
                    'id',
                    'buyer_id',
                    'amount',
                    'payment_fee'
                ])
                ->with([
                    'buyer' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
                            'appearanceCase' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id'
                                ])->with([
                                    'vybe' => function ($query) {
                                        $query->select([
                                            'id',
                                            'activity_id',
                                            'title'
                                        ])->with([
                                            'activity' => function ($query) {
                                                $query->select([
                                                    'id',
                                                    'code',
                                                    'name'
                                                ]);
                                            }
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
                            'timeslot' => function ($query) {
                                $query->select([
                                    'id',
                                    'datetime_from',
                                    'datetime_to'
                                ]);
                            }
                        ])->orderBy('id', 'desc');
                    }
                ])
                ->whereIn('id', $ordersIds)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
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
            return Order::query()
                ->with([
                    'buyer' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
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
                                            'type_id'
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
                        ]);
                    }
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
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
            return Order::query()
                ->select([
                    'id',
                    'buyer_id',
                    'amount',
                    'created_at'
                ])
                ->with([
                    'buyer' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
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
                                            'type_id'
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
                        ]);
                    }
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        ?int $orderOverviewId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?int $orderItemId = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return Order::query()
                ->select([
                    'id',
                    'buyer_id',
                    'amount',
                    'created_at'
                ])
                ->with([
                    'buyer' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
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
                                            'type_id'
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
                        ]);
                    }
                ])
                ->when($orderOverviewId, function ($query) use ($orderOverviewId) {
                    $query->where('id', '=', $orderOverviewId);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('items.appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('items', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('items.appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemPaymentStatusesIds, function ($query) use ($orderItemPaymentStatusesIds) {
                    $query->whereHas('items', function ($query) use ($orderItemPaymentStatusesIds) {
                        $query->whereIn('payment_status_id', $orderItemPaymentStatusesIds);
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_overview_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('users.id', 'orders.buyer_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('vybes', 'vybes.user_id', '=', 'users.id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_id') {
                        $query->orderBy(
                            OrderItem::query()->select('id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount', $sortOrder);
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->take(1),
                            $sortOrder
                        );
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllPaginatedFiltered(
        ?int $orderOverviewId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?int $orderItemId = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Order::query()
                ->select([
                    'id',
                    'buyer_id',
                    'amount',
                    'created_at'
                ])
                ->with([
                    'buyer' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
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
                                            'type_id'
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
                        ]);
                    }
                ])
                ->when($orderOverviewId, function ($query) use ($orderOverviewId) {
                    $query->where('id', '=', $orderOverviewId);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('items.appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('items', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('items.appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemPaymentStatusesIds, function ($query) use ($orderItemPaymentStatusesIds) {
                    $query->whereHas('items', function ($query) use ($orderItemPaymentStatusesIds) {
                        $query->whereIn('payment_status_id', $orderItemPaymentStatusesIds);
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_overview_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('users.id', 'orders.buyer_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('vybes', 'vybes.user_id', '=', 'users.id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_id') {
                        $query->orderBy(
                            OrderItem::query()->select('id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount', $sortOrder);
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->take(1),
                            $sortOrder
                        );
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $orderOverviewId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param string|null $seller
     * @param int|null $orderItemId
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForAdminLabels(
        ?int $orderOverviewId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?int $orderItemId = null
    ) : Collection
    {
        try {
            return Order::query()
                ->select([
                    'id'
                ])
                ->with([
                    'items' => function ($query) {
                        $query->select([
                            'id',
                            'order_id',
                            'vybe_id'
                        ]);
                    }
                ])
                ->when($orderOverviewId, function ($query) use ($orderOverviewId) {
                    $query->where('id', '=', $orderOverviewId);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('items.appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('items', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllForBuyerFiltered(
        User $buyer,
        ?int $orderOverviewId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $seller = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return Order::query()
                ->select([
                    'id',
                    'buyer_id',
                    'amount',
                    'created_at'
                ])
                ->with([
                    'buyer' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
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
                                            'type_id'
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
                        ]);
                    }
                ])
                ->when($orderOverviewId, function ($query) use ($orderOverviewId) {
                    $query->where('id', '=', $orderOverviewId);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('items.appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('items.appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemPaymentStatusesIds, function ($query) use ($orderItemPaymentStatusesIds) {
                    $query->whereHas('items', function ($query) use ($orderItemPaymentStatusesIds) {
                        $query->whereIn('payment_status_id', $orderItemPaymentStatusesIds);
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_overview_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('vybes', 'vybes.user_id', '=', 'users.id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount', $sortOrder);
                    }

                    if ($sortBy == 'payment_fee') {
                        $query->orderBy('payment_fee', $sortOrder);
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->take(1),
                            $sortOrder
                        );
                    }
                })
                ->where('buyer_id', '=', $buyer->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllForBuyerPaginatedFiltered(
        User $buyer,
        ?int $orderOverviewId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $seller = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Order::query()
                ->select([
                    'id',
                    'buyer_id',
                    'amount',
                    'created_at'
                ])
                ->with([
                    'buyer' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
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
                                            'type_id'
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
                        ]);
                    }
                ])
                ->when($orderOverviewId, function ($query) use ($orderOverviewId) {
                    $query->where('id', '=', $orderOverviewId);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('items.appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('items.appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemPaymentStatusesIds, function ($query) use ($orderItemPaymentStatusesIds) {
                    $query->whereHas('items', function ($query) use ($orderItemPaymentStatusesIds) {
                        $query->whereIn('payment_status_id', $orderItemPaymentStatusesIds);
                    });
                })
                ->when($orderItemPaymentStatusesIds, function ($query) use ($orderItemPaymentStatusesIds) {
                    $query->whereHas('items', function ($query) use ($orderItemPaymentStatusesIds) {
                        $query->whereIn('payment_status_id', $orderItemPaymentStatusesIds);
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_overview_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('vybes', 'vybes.user_id', '=', 'users.id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount', $sortOrder);
                    }

                    if ($sortBy == 'payment_fee') {
                        $query->orderBy('payment_fee', $sortOrder);
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->whereColumn('order_items.order_id', 'orders.id')
                                ->take(1),
                            $sortOrder
                        );
                    }
                })
                ->where('buyer_id', '=', $buyer->id)
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $buyer
     * @param int|null $orderOverviewId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $seller
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForBuyerFilteredForAdminLabels(
        User $buyer,
        ?int $orderOverviewId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $seller = null
    ) : Collection
    {
        try {
            return Order::query()
                ->select([
                    'id'
                ])
                ->with([
                    'items' => function ($query) {
                        $query->select([
                            'id',
                            'order_id',
                            'vybe_id'
                        ]);
                    }
                ])
                ->when($orderOverviewId, function ($query) use ($orderOverviewId) {
                    $query->where('id', '=', $orderOverviewId);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('items.appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->where('buyer_id', '=', $buyer->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $buyer
     * @param PaymentMethod $paymentMethod
     * @param float|null $amount
     * @param float $amountTax
     * @param float|null $amountTotal
     * @param float $paymentFee
     * @param float $paymentFeeTax
     * @param string|null $paidAt
     *
     * @return Order|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $buyer,
        PaymentMethod $paymentMethod,
        ?float $amount = null,
        float $amountTax = 0,
        ?float $amountTotal = null,
        float $paymentFee = 0,
        float $paymentFeeTax = 0,
        string $paidAt = null
    ) : ?Order
    {

        try {
            return Order::query()->create([
                'buyer_id'        => $buyer->id,
                'method_id'       => $paymentMethod->id,
                'amount'          => $amount,
                'amount_tax'      => $amountTax,
                'amount_total'    => $amountTotal,
                'payment_fee'     => $paymentFee,
                'payment_fee_tax' => $paymentFeeTax,
                'hash'            => generatePaymentHash(),
                'paid_at'         => $paidAt ?
                    Carbon::parse($paidAt)->format('Y-m-d H:i:s') :
                    null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
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
    ) : Order
    {
        try {
            $order->update([
                'buyer_id'        => $buyer ? $buyer->id : $order->buyer_id,
                'method_id'       => $paymentMethod ? $paymentMethod->id : $order->method_id,
                'amount'          => $amount ?: $order->amount,
                'amount_tax'      => $amountTax ?: $order->amount_tax,
                'amount_total'    => $amountTotal ?: $order->amount_total,
                'payment_fee'     => $paymentFee ?: $order->payment_fee,
                'payment_fee_tax' => $paymentFeeTax ?: $order->payment_fee_tax,
                'paid_at'         => $paidAt ?
                    Carbon::parse($paidAt)->format('Y-m-d H:i:s') :
                    $order->paid_at
            ]);

            return $order;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Order $order
     * @param float|null $amount
     * @param float|null $amountTax
     * @param float|null $amountTotal
     *
     * @return Order
     *
     * @throws DatabaseException
     */
    public function updateAmount(
        Order $order,
        ?float $amount = null,
        ?float $amountTax = null,
        ?float $amountTotal = null
    ) : Order
    {
        try {
            $order->update([
                'amount'       => $amount ?: $order->amount,
                'amount_tax'   => $amountTax ?: $order->amount_tax,
                'amount_total' => $amountTotal ?: $order->amount_total
            ]);

            return $order;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Order $order
     * @param float $amountTotal
     *
     * @return Order
     *
     * @throws DatabaseException
     */
    public function updateAmountTotal(
        Order $order,
        float $amountTotal
    ) : Order
    {
        try {
            $order->update([
                'amount_total' => $amountTotal
            ]);

            return $order;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Order $order
     * @param float $amountTax
     *
     * @return Order
     *
     * @throws DatabaseException
     */
    public function updateAmountTax(
        Order $order,
        float $amountTax
    ) : Order
    {
        try {
            $order->update([
                'amount_tax' => $amountTax
            ]);

            return $order;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Order $order
     * @param float|null $paymentFee
     * @param float|null $paymentFeeTax
     *
     * @return Order
     *
     * @throws DatabaseException
     */
    public function updatePaymentFee(
        Order $order,
        ?float $paymentFee,
        ?float $paymentFeeTax
    ) : Order
    {
        try {
            $order->update([
                'payment_fee'     => $paymentFee,
                'payment_fee_tax' => $paymentFeeTax
            ]);

            return $order;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Order $order
     *
     * @return Order
     *
     * @throws DatabaseException
     */
    public function updatePaidAt(
        Order $order
    ) : Order
    {
        try {
            $order->update([
                'paid_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            return $order;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Order $order
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Order $order
    ) : bool
    {
        try {
            return $order->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/order.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
