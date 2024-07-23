<?php

namespace App\Repositories\Sale;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Sale;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Sale\Interfaces\SaleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class SaleRepository
 *
 * @package App\Repositories\Sale
 */
class SaleRepository extends BaseRepository implements SaleRepositoryInterface
{
    /**
     * SaleRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.sale.cacheTime');
        $this->perPage = config('repositories.sale.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Sale|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Sale
    {
        try {
            return Sale::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return Sale|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?int $id
    ) : ?Sale
    {
        try {
            return Sale::query()
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
                                                ])->with([
                                                    'category' => function ($query) {
                                                        $query->select([
                                                            'id',
                                                            'name',
                                                            'code',
                                                            'parent_id'
                                                        ])->with([
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
                            'invoices' => function ($query) {
                                $query->select([
                                    'id',
                                    'type_id',
                                    'status_id'
                                ])->where('type_id', '=', 2);
                            },
                            'timeslot' => function ($query) {
                                $query->select([
                                    'id',
                                    'datetime_from',
                                    'datetime_to'
                                ]);
                            }
                        ]);
                    }
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
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
            return Cache::remember('sales.all.count', $this->cacheTime,
                function () {
                    return Order::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
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
            return Sale::query()
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
                trans('exceptions/repository/sale.' . __FUNCTION__),
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
            return Sale::query()
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
                trans('exceptions/repository/sale.' . __FUNCTION__),
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
        ?int $id = null,
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
            return Sale::query()
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
                    'items' => function ($query) {
                        $query->with([
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
                    if ($sortBy == 'sale_overview_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.id', '=', 'sales.order_id')
                                ->whereColumn('users.id', 'orders.buyer_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('users.id', 'sales.seller_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_id') {
                        $query->orderBy(
                            OrderItem::query()->select('id')
                                ->join('order_item_sale', 'order_item_sale.sale_id', '=', 'sales.id')
                                ->whereColumn('order_items.id', '=', 'order_item_sale.item_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('order_item_sale', 'order_item_sale.sale_id', '=', 'sales.id')
                                ->whereColumn('order_items.order_id', 'sales.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->join('order_item_sale', 'order_item_sale.sale_id', '=', 'sales.id')
                                ->whereColumn('order_items.order_id', 'sales.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
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
        ?int $id = null,
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
            return Sale::query()
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
                    'items' => function ($query) {
                        $query->with([
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
                    $query->whereIn('items.payment_status_id', $orderItemPaymentStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'sale_overview_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.id', '=', 'sales.order_id')
                                ->whereColumn('users.id', 'orders.buyer_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('users.id', 'sales.seller_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_id') {
                        $query->orderBy(
                            OrderItem::query()->select('id')
                                ->join('order_item_sale', 'order_item_sale.sale_id', '=', 'sales.id')
                                ->whereColumn('order_items.id', '=', 'order_item_sale.item_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('order_item_sale', 'order_item_sale.sale_id', '=', 'sales.id')
                                ->whereColumn('order_items.order_id', 'sales.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->join('order_item_sale', 'order_item_sale.sale_id', '=', 'sales.id')
                                ->whereColumn('order_items.order_id', 'sales.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
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
     * @param int|null $orderItemId
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
        ?int $orderItemId = null
    ) : Collection
    {
        try {
            return Sale::query()
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
                trans('exceptions/repository/sale.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForUser(
        User $user,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?int $orderItemId = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return Sale::query()
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
                    'items' => function ($query) {
                        $query->with([
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
                    if ($sortBy == 'sale_overview_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.id', '=', 'sales.order_id')
                                ->whereColumn('users.id', 'orders.buyer_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_id') {
                        $query->orderBy(
                            OrderItem::query()->select('id')
                                ->join('order_item_sale', 'order_item_sale.sale_id', '=', 'sales.id')
                                ->whereColumn('order_items.id', '=', 'order_item_sale.item_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('order_item_sale', 'order_item_sale.sale_id', '=', 'sales.id')
                                ->whereColumn('order_items.order_id', 'sales.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->join('order_item_sale', 'order_item_sale.sale_id', '=', 'sales.id')
                                ->whereColumn('order_items.order_id', 'sales.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }
                })
                ->where('seller_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllPaginatedFilteredForUser(
        User $user,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
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
            return Sale::query()
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
                    'items' => function ($query) {
                        $query->with([
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
                    if ($sortBy == 'sale_overview_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'order_date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.id', '=', 'sales.order_id')
                                ->whereColumn('users.id', 'orders.buyer_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_id') {
                        $query->orderBy(
                            OrderItem::query()->select('id')
                                ->join('order_item_sale', 'order_item_sale.sale_id', '=', 'sales.id')
                                ->whereColumn('order_items.id', '=', 'order_item_sale.item_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('order_item_sale', 'order_item_sale.sale_id', '=', 'sales.id')
                                ->whereColumn('order_items.order_id', 'sales.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->join('order_item_sale', 'order_item_sale.sale_id', '=', 'sales.id')
                                ->whereColumn('order_items.order_id', 'sales.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }
                })
                ->where('seller_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param int|null $orderItemId
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForUserForAdminLabels(
        User $user,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?int $orderItemId = null
    ) : Collection
    {
        try {
            return Sale::query()
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
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('items', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->where('seller_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Order $order
     * @param User $seller
     * @param float $amountEarned
     * @param float $amountTotal
     * @param float $handlingFee
     *
     * @return Sale|null
     *
     * @throws DatabaseException
     */
    public function store(
        Order $order,
        User $seller,
        float $amountEarned,
        float $amountTotal,
        float $handlingFee
    ) : ?Sale
    {
        try {
            return Sale::query()->create([
                'order_id'      => $order->id,
                'seller_id'     => $seller->id,
                'amount_earned' => $amountEarned,
                'amount_total'  => $amountTotal,
                'handling_fee'  => $handlingFee
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Sale $sale
     * @param Order|null $order
     * @param User|null $seller
     * @param float|null $amountEarned
     * @param float|null $amountTotal
     * @param float|null $handlingFee
     *
     * @return Sale
     *
     * @throws DatabaseException
     */
    public function update(
        Sale $sale,
        ?Order $order,
        ?User $seller,
        ?float $amountEarned,
        ?float $amountTotal,
        ?float $handlingFee
    ) : Sale
    {
        try {
            $sale->update([
                'order_id'      => $order ? $order->id : $sale->order_id,
                'seller_id'     => $seller ? $seller->id : $sale->seller_id,
                'amount_earned' => $amountEarned ?: $sale->amount_earned,
                'amount_total'  => $amountTotal ?: $sale->amount_total,
                'handling_fee'  => $handlingFee ?: $sale->handling_fee
            ]);

            return $sale;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Sale $sale
     * @param OrderItem $orderItem
     *
     * @throws DatabaseException
     */
    public function attachOrderItem(
        Sale $sale,
        OrderItem $orderItem
    ) : void
    {
        try {
            $sale->items()->sync([
                $orderItem->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Sale $sale
     * @param array $orderItemsIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachOrderItems(
        Sale $sale,
        array $orderItemsIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $sale->items()->sync(
                $orderItemsIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Sale $sale
     * @param OrderItem $orderItem
     *
     * @throws DatabaseException
     */
    public function detachOrderItem(
        Sale $sale,
        OrderItem $orderItem
    ) : void
    {
        try {
            $sale->items()->detach([
                $orderItem->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Sale $sale
     * @param array $orderItemsIds
     *
     * @throws DatabaseException
     */
    public function detachOrderItems(
        Sale $sale,
        array $orderItemsIds
    ) : void
    {
        try {
            $sale->items()->detach(
                $orderItemsIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Sale $sale
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Sale $sale
    ) : bool
    {
        try {
            return $sale->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/sale.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}