<?php

namespace App\Repositories\Order;

use App\Exceptions\DatabaseException;
use App\Lists\Invoice\Status\InvoiceStatusListItem;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Lists\Invoice\Type\InvoiceTypeListItem;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Order\Interfaces\OrderInvoiceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class OrderInvoiceRepository
 *
 * @package App\Repositories\Order
 */
class OrderInvoiceRepository extends BaseRepository implements OrderInvoiceRepositoryInterface
{
    /**
     * OrderInvoiceRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.orderInvoice.cacheTime');
        $this->perPage = config('repositories.orderInvoice.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return OrderInvoice|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?OrderInvoice
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param InvoiceTypeListItem|null $invoiceTypeListItem
     * @param int|null $id
     *
     * @return OrderInvoice|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?InvoiceTypeListItem $invoiceTypeListItem,
        ?int $id
    ) : ?OrderInvoice
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ])->with([
                                    'billing' => function ($query) {
                                        $query->with([
                                            'countryPlace',
                                            'regionPlace'
                                        ]);
                                    }
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
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
                                            'activity_id',
                                            'type_id',
                                            'status_id',
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
                                    }
                                ]);
                            },
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'activity_id',
                                    'type_id',
                                    'status_id',
                                    'title'
                                ]);
                            },
                            'seller' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ])->with([
                                    'billing' => function ($query) {
                                        $query->with([
                                            'countryPlace',
                                            'regionPlace'
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
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'method_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
                        ])->with([
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($invoiceTypeListItem, function ($query) use ($invoiceTypeListItem) {
                    $query->where('type_id', '=', $invoiceTypeListItem->id);
                })
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Order $order
     *
     * @return OrderInvoice|null
     *
     * @throws DatabaseException
     */
    public function findByOrderForBuyer(
        Order $order
    ) : ?OrderInvoice
    {
        try {
            return $order->invoices
                ->where('type_id', '=', InvoiceTypeList::getBuyer()->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getAllForBuyerCount() : int
    {
        try {
            return Cache::remember('orderInvoices.buyers.all.count', $this->cacheTime,
                function () {
                    return OrderInvoice::query()
                        ->where('type_id', '=', InvoiceTypeList::getBuyer()->id)
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getAllForSellerCount() : int
    {
        try {
            return Cache::remember('orderInvoices.sellers.all.count', $this->cacheTime,
                function () {
                    return OrderInvoice::query()
                        ->where('type_id', '=', InvoiceTypeList::getSeller()->id)
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getAllForAffiliateCount() : int
    {
        try {
            return Cache::remember('orderInvoices.affiliates.all.count', $this->cacheTime,
                function () {
                    return OrderInvoice::query()
                        ->where('type_id', '=', InvoiceTypeList::getAffiliate()->id)
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param InvoiceTypeListItem|null $invoiceTypeListItem
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll(
        ?InvoiceTypeListItem $invoiceTypeListItem = null
    ) : Collection
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
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
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
                        ]);
                    }
                ])
                ->when($invoiceTypeListItem, function ($query) use ($invoiceTypeListItem) {
                    $query->where('type_id', '=', $invoiceTypeListItem->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param InvoiceTypeListItem|null $invoiceTypeListItem
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?InvoiceTypeListItem $invoiceTypeListItem = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
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
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
                        ]);
                    }
                ])
                ->when($invoiceTypeListItem, function ($query) use ($invoiceTypeListItem) {
                    $query->where('type_id', '=', $invoiceTypeListItem->id);
                })
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getFilteredForBuyer(
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo  = null,
        ?int $orderOverviewId  = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'avatar_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
                            'order' => function ($query) {
                                $query->select([
                                    'id',
                                    'buyer_id',
                                    'method_id',
                                    'created_at'
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
                                                    'avatar_id',
                                                    'username'
                                                ]);
                                            }
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
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
                ->when($orderOverviewId, function ($query) use ($orderOverviewId) {
                    $query->whereHas('order', function ($query) use ($orderOverviewId) {
                        $query->where('id', '=', $orderOverviewId);
                    });
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
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'order_overview_id') {
                        $query->orderBy('order_id', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.buyer_id', '=', 'users.id')
                                ->whereColumn('orders.id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('vybes', 'vybes.user_id', '=', 'users.id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                ->whereColumn('order_items.order_id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy(
                            OrderItem::query()->select('amount_total')
                                ->join('invoice_order_item', 'invoice_order_item.item_id', '=', 'order_items.id')
                                ->whereColumn('invoice_order_item.invoice_id', 'order_invoices.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'handling_fee') {
                        $query->orderBy(
                            OrderItem::query()->select(DB::raw('SUM(handling_fee) as handling_fee'))
                                ->join('invoice_order_item', 'invoice_order_item.item_id', '=', 'order_items.id')
                                ->whereColumn('invoice_order_item.invoice_id', 'order_invoices.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('invoice_order_item', 'invoice_order_item.item_id', '=', 'order_items.id')
                                ->whereColumn('invoice_order_item.invoice_id', 'order_invoices.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->join('invoice_order_item', 'invoice_order_item.item_id', '=', 'order_items.id')
                                ->whereColumn('invoice_order_item.invoice_id', 'order_invoices.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'invoice_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->whereIn('type_id', InvoiceTypeList::getAllForBuyer()
                    ->pluck('id')
                    ->toArray()
                )
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getFilteredForSeller(
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo  = null,
        ?int $vybeVersion = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
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
                        ]);
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
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
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->whereHas('items', function ($query) use ($vybeVersion) {
                        $query->where('vybe_version', '=', $vybeVersion);
                    });
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
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('items.appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('items', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'vybe_version') {
                        $query->orderBy(
                            OrderItem::query()->select('vybe_version')
                                ->join('invoice_order_item', 'invoice_order_item.item_id', '=', 'order_items.id')
                                ->whereColumn('invoice_order_item.invoice_id', 'order_invoices.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('vybes', 'vybes.user_id', '=', 'users.id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                ->whereColumn('order_items.order_id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.buyer_id', '=', 'users.id')
                                ->whereColumn('orders.id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy(
                            OrderItem::query()->select('amount_total')
                                ->join('invoice_order_item', 'invoice_order_item.item_id', '=', 'order_items.id')
                                ->whereColumn('invoice_order_item.invoice_id', 'order_invoices.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'handling_fee') {
                        $query->orderBy(
                            OrderItem::query()->select('handling_fee')
                                ->join('invoice_order_item', 'invoice_order_item.item_id', '=', 'order_items.id')
                                ->whereColumn('invoice_order_item.invoice_id', 'order_invoices.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'earned') {
                        $query->orderBy(
                            OrderItem::query()->select('amount_earned')
                                ->join('invoice_order_item', 'invoice_order_item.item_id', '=', 'order_items.id')
                                ->whereColumn('invoice_order_item.invoice_id', 'order_invoices.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('invoice_order_item', 'invoice_order_item.item_id', '=', 'order_items.id')
                                ->whereColumn('invoice_order_item.invoice_id', 'order_invoices.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy(
                            OrderItem::query()->select('status_id')
                                ->join('invoice_order_item', 'invoice_order_item.item_id', '=', 'order_items.id')
                                ->whereColumn('invoice_order_item.invoice_id', 'order_invoices.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'invoice_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->whereIn('type_id', InvoiceTypeList::getAllForSeller()
                    ->pluck('id')
                    ->toArray()
                )
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $orderOverviewId
     * @param string|null $buyer
     * @param string|null $seller
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFilteredForBuyerForAdminLabels(
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $orderOverviewId = null,
        ?string $buyer = null,
        ?string $seller = null
    ) : Collection
    {
        try {
            return OrderInvoice::query()
                ->select([
                    'id'
                ])
                ->with([
                    'items' => function ($query) {
                        $query->select([
                            'id',
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
                ->when($orderOverviewId, function ($query) use ($orderOverviewId) {
                    $query->whereHas('order', function ($query) use ($orderOverviewId) {
                        $query->where('id', '=', $orderOverviewId);
                    });
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
                ->whereIn('type_id', InvoiceTypeList::getAllForBuyer()
                    ->pluck('id')
                    ->toArray()
                )
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getPaginatedFilteredForBuyer(
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo  = null,
        ?int $orderOverviewId  = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
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
                        ]);
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
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
                ->when($orderOverviewId, function ($query) use ($orderOverviewId) {
                    $query->whereHas('order', function ($query) use ($orderOverviewId) {
                        $query->whereIn('id', $orderOverviewId);
                    });
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
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'order_overview_id') {
                        $query->orderBy('order_id', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.id', '=', 'order_invoices.order_id')
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
                                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                ->whereColumn('order_items.order_id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy(
                            OrderItem::query()->select('amount_total')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'handling_fee') {
                        $query->orderBy(
                            OrderItem::query()->select('handling_fee')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('order_items.order_id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'invoice_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->whereIn('type_id', InvoiceTypeList::getAllForBuyer()
                    ->pluck('id')
                    ->toArray()
                )
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getPaginatedFilteredForSeller(
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo  = null,
        ?int $vybeVersion = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
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
                        ]);
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
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
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->whereHas('items.appearanceCase.vybe', function ($query) use ($vybeVersion) {
                        $query->whereIn('vybe_version', $vybeVersion);
                    });
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
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'order_overview_id') {
                        $query->orderBy('order_id', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.id', '=', 'order_invoices.order_id')
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
                                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                ->whereColumn('order_items.order_id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy(
                            OrderItem::query()->select('amount_total')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'handling_fee') {
                        $query->orderBy(
                            OrderItem::query()->select('handling_fee')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('order_items.order_id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'invoice_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->whereIn('type_id', InvoiceTypeList::getAllForSeller()
                    ->pluck('id')
                    ->toArray()
                )
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $vybeVersion
     * @param string|null $buyer
     * @param string|null $seller
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFilteredForSellerForAdminLabels(
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo  = null,
        ?int $vybeVersion = null,
        ?string $buyer = null,
        ?string $seller = null
    ) : Collection
    {
        try {
            return OrderInvoice::query()
                ->select([
                    'id'
                ])
                ->with([
                    'items' => function ($query) {
                        $query->select([
                            'id',
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
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->whereHas('items', function ($query) use ($vybeVersion) {
                        $query->where('vybe_version', '=', $vybeVersion);
                    });
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
                ->whereIn('type_id', InvoiceTypeList::getAllForSeller()
                    ->pluck('id')
                    ->toArray()
                )
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getFilteredByUserForBuyer(
        User $buyer,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $orderOverviewId = null,
        ?string $seller = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
                            'order' => function ($query) {
                                $query->select([
                                    'id',
                                    'buyer_id',
                                    'method_id',
                                    'created_at'
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
                        ]);
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
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
                ->when($orderOverviewId, function ($query) use ($orderOverviewId) {
                    $query->whereHas('order', function ($query) use ($orderOverviewId) {
                        $query->where('id', '=', $orderOverviewId);
                    });
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
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->whereIn('type_id', InvoiceTypeList::getAllForBuyer()
                    ->pluck('id')
                    ->toArray()
                )
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'order_overview_id') {
                        $query->orderBy('order_id', $sortOrder);
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('vybes', 'vybes.user_id', '=', 'users.id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                ->whereColumn('order_items.order_id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy(
                            OrderItem::query()->select('amount_total')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('order_items.order_id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'invoice_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->whereHas('order', function($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getPaginatedFilteredByUserForBuyer(
        User $buyer,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $orderOverviewId = null,
        ?string $seller = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
                            'order' => function ($query) {
                                $query->select([
                                    'id',
                                    'buyer_id',
                                    'method_id',
                                    'created_at'
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
                        ]);
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
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
                ->when($orderOverviewId, function ($query) use ($orderOverviewId) {
                    $query->whereHas('order', function ($query) use ($orderOverviewId) {
                        $query->where('id', '=', $orderOverviewId);
                    });
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
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'order_overview_id') {
                        $query->orderBy('order_id', $sortOrder);
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('vybes', 'vybes.user_id', '=', 'users.id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                                ->whereColumn('order_items.order_id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy(
                            OrderItem::query()->select('amount_total')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('order_items.order_id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'invoice_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->whereIn('type_id', InvoiceTypeList::getAllForBuyer()
                    ->pluck('id')
                    ->toArray()
                )
                ->whereHas('order', function($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $buyer
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $orderOverviewId
     * @param string|null $seller
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFilteredByUserForBuyerForAdminLabels(
        User $buyer,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $orderOverviewId = null,
        ?string $seller = null
    ) : Collection
    {
        try {
            return OrderInvoice::query()
                ->select([
                    'id'
                ])
                ->with([
                    'items' => function ($query) {
                        $query->select([
                            'id',
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
                ->when($orderOverviewId, function ($query) use ($orderOverviewId) {
                    $query->whereHas('order', function ($query) use ($orderOverviewId) {
                        $query->where('id', '=', $orderOverviewId);
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('items.appearanceCase.vybe.user', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->whereIn('type_id', InvoiceTypeList::getAllForBuyer()
                    ->pluck('id')
                    ->toArray()
                )
                ->whereHas('order', function($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
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
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFilteredByUserForSeller(
        User $seller,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
                            'order' => function ($query) {
                                $query->select([
                                    'id',
                                    'buyer_id',
                                    'method_id',
                                    'created_at'
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
                        ]);
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
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
                    $query->whereHas('order.buyer', function ($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer .'%');
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
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.id', '=', 'order_invoices.order_id')
                                ->whereColumn('users.id', 'orders.buyer_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'earned') {
                        $query->orderBy(
                            OrderItem::query()->select('amount_earned')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('order_items.order_id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'invoice_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->whereIn('type_id', InvoiceTypeList::getAllForSeller()
                    ->pluck('id')
                    ->toArray()
                )
                ->whereHas('items', function($query) use ($seller) {
                    $query->where('seller_id', '=', $seller->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
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
     * @param array|null $vybeTypesIds
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
    public function getPaginatedFilteredByUserForSeller(
        User $seller,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
                            'order' => function ($query) {
                                $query->select([
                                    'id',
                                    'buyer_id',
                                    'method_id',
                                    'created_at'
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
                        ]);
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
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
                    $query->whereHas('order.buyer', function ($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer .'%');
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
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('orders', 'orders.id', '=', 'order_invoices.order_id')
                                ->whereColumn('users.id', 'orders.buyer_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'earned') {
                        $query->orderBy(
                            OrderItem::query()->select('amount_earned')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('order_items.order_id', 'order_invoices.order_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_payment_status') {
                        $query->orderBy(
                            OrderItem::query()->select('payment_status_id')
                                ->join('invoice_order_item', 'invoice_order_item.invoice_id', '=', 'order_invoices.id')
                                ->whereColumn('invoice_order_item.item_id', 'order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'invoice_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->whereIn('type_id', InvoiceTypeList::getAllForSeller()
                    ->pluck('id')
                    ->toArray()
                )
                ->whereHas('items', function($query) use ($seller) {
                    $query->where('seller_id', '=', $seller->id);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
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
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFilteredByUserForSellerForAdminLabels(
        User $seller,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null
    ) : Collection
    {
        try {
            return OrderInvoice::query()
                ->select([
                    'id'
                ])
                ->with([
                    'items' => function ($query) {
                        $query->select([
                            'id',
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
                    $query->whereHas('order.buyer', function ($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer .'%');
                    });
                })
                ->whereIn('type_id', InvoiceTypeList::getAllForSeller()
                    ->pluck('id')
                    ->toArray()
                )
                ->whereHas('items', function($query) use ($seller) {
                    $query->where('seller_id', '=', $seller->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $orderInvoicesIds
     *
     * @return OrderInvoice
     *
     * @throws DatabaseException
     */
    public function getForBuyerStatusesByIdsCount(
        array $orderInvoicesIds
    ) : OrderInvoice
    {
        try {
            return OrderInvoice::query()
                ->whereIn('id', $orderInvoicesIds)
                ->selectRaw('sum(case when status_id = 1 then 1 else 0 end) as unpaid')
                ->selectRaw('sum(case when status_id = 4 then 1 else 0 end) as paid')
                ->selectRaw('sum(case when status_id = 5 then 1 else 0 end) as canceled')
                ->selectRaw('sum(case when status_id = 6 then 1 else 0 end) as credit')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $orderInvoicesIds
     *
     * @return OrderInvoice
     *
     * @throws DatabaseException
     */
    public function getForSellerStatusesByIdsCount(
        array $orderInvoicesIds
    ) : OrderInvoice
    {
        try {
            return OrderInvoice::query()
                ->whereIn('id', $orderInvoicesIds)
                ->selectRaw('sum(case when status_id = 2 then 1 else 0 end) as on_hold')
                ->selectRaw('sum(case when status_id = 3 then 1 else 0 end) as pending_payout')
                ->selectRaw('sum(case when status_id = 4 then 1 else 0 end) as paid')
                ->selectRaw('sum(case when status_id = 5 then 1 else 0 end) as canceled')
                ->selectRaw('sum(case when status_id = 6 then 1 else 0 end) as credit')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getForDashboardBuyerFiltered(
        User $buyer,
        ?int $invoiceId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $username = null,
        ?int $total = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?array $invoiceStatusesIds = null
    ) : Collection
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
                            'order' => function ($query) {
                                $query->select([
                                    'id',
                                    'buyer_id',
                                    'method_id',
                                    'created_at'
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
                        ]);
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
                        ]);
                    }
                ])
                ->when($invoiceId, function ($query) use ($invoiceId) {
                    $query->where('id', '=', $invoiceId);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('items.seller', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username .'%');
                    });
                })
                ->when($total, function ($query) use ($total) {
                    $query->whereHas('items', function ($query) use ($total) {
                        $query->where('amount', '=', $total);
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
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->where('type_id', '=', InvoiceTypeList::getBuyer()->id)
                ->whereHas('order', function($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $buyer
     * @param int|null $invoiceId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $total
     * @param array|null $vybeTypesIds
     * @param array|null $orderItemPaymentStatusesIds
     * @param array|null $invoiceStatusesIds
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getForDashboardBuyerFilteredPaginated(
        User $buyer,
        ?int $invoiceId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $username = null,
        ?int $total = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemPaymentStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
                            'order' => function ($query) {
                                $query->select([
                                    'id',
                                    'buyer_id',
                                    'method_id',
                                    'created_at'
                                ]);
                            },
                            'vybe' => function ($query) {
                                $query->select(
                                    'id',
                                    'title',
                                    'type_id'
                                );
                            },
                            'seller' => function ($query) {
                                $query->select(
                                    'id',
                                    'auth_id',
                                    'username'
                                );
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
                        ]);
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
                        ]);
                    }
                ])
                ->when($invoiceId, function ($query) use ($invoiceId) {
                    $query->where('id', '=', $invoiceId);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('items.appearanceCase.vybe.user', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username .'%');
                    });
                })
                ->when($total, function ($query) use ($total) {
                    $query->whereHas('items', function ($query) use ($total) {
                        $query->where('amount', '=', $total);
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
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->where('type_id', '=', InvoiceTypeList::getBuyer()->id)
                ->whereHas('order', function($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getForDashboardSellerFiltered(
        User $seller,
        ?int $invoiceId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $username = null,
        ?int $earned = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $invoiceStatusesIds = null
    ) : Collection
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
                            'order' => function ($query) {
                                $query->select([
                                    'id',
                                    'buyer_id',
                                    'method_id',
                                    'created_at'
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
                        ]);
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
                        ]);
                    }
                ])
                ->when($invoiceId, function ($query) use ($invoiceId) {
                    $query->where('id', '=', $invoiceId);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('items.seller', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username .'%');
                    });
                })
                ->when($earned, function ($query) use ($earned) {
                    $query->whereHas('items', function ($query) use ($earned) {
                        $query->where('amount_earned', '=', $earned);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('items.appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('items', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->where('type_id', '=', InvoiceTypeList::getSeller()->id)
                ->whereHas('items', function($query) use ($seller) {
                    $query->where('seller_id', '=', $seller->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getForDashboardSellerFilteredPaginated(
        User $seller,
        ?int $invoiceId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $username = null,
        ?int $earned = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return OrderInvoice::query()
                ->with([
                    'order' => function ($query) {
                        $query->select([
                            'id',
                            'buyer_id',
                            'method_id',
                            'created_at'
                        ])->with([
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    },
                    'items' => function ($query) {
                        $query->with([
                            'order' => function ($query) {
                                $query->select([
                                    'id',
                                    'buyer_id',
                                    'method_id',
                                    'created_at'
                                ]);
                            },
                            'vybe' => function ($query) {
                                $query->select(
                                    'id',
                                    'title',
                                    'type_id'
                                );
                            },
                            'seller' => function ($query) {
                                $query->select(
                                    'id',
                                    'auth_id',
                                    'username'
                                );
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
                        ]);
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'invoice_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
                        ]);
                    }
                ])
                ->when($invoiceId, function ($query) use ($invoiceId) {
                    $query->where('id', '=', $invoiceId);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('items.appearanceCase.vybe.user', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username .'%');
                    });
                })
                ->when($earned, function ($query) use ($earned) {
                    $query->whereHas('items', function ($query) use ($earned) {
                        $query->where('amount_earned', '=', $earned);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('items.appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('items', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->where('type_id', '=', InvoiceTypeList::getSeller()->id)
                ->whereHas('items', function($query) use ($seller) {
                    $query->where('seller_id', '=', $seller->id);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderInvoice|null $parentInvoice
     * @param Order $order
     * @param InvoiceTypeListItem $invoiceTypeListItem
     * @param InvoiceStatusListItem $invoiceStatusListItem
     *
     * @return OrderInvoice|null
     *
     * @throws DatabaseException
     */
    public function store(
        ?OrderInvoice $parentInvoice,
        Order $order,
        InvoiceTypeListItem $invoiceTypeListItem,
        InvoiceStatusListItem $invoiceStatusListItem
    ) : ?OrderInvoice
    {
        try {
            return OrderInvoice::query()->create([
                'parent_id' => $parentInvoice?->id,
                'order_id'  => $order->id,
                'type_id'   => $invoiceTypeListItem->id,
                'status_id' => $invoiceStatusListItem->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderInvoice $orderInvoice
     * @param OrderInvoice|null $parentInvoice
     * @param Order|null $order
     * @param InvoiceTypeListItem|null $invoiceTypeListItem
     * @param InvoiceStatusListItem|null $invoiceStatusListItem
     *
     * @return OrderInvoice
     *
     * @throws DatabaseException
     */
    public function update(
        OrderInvoice $orderInvoice,
        ?OrderInvoice $parentInvoice,
        ?Order $order,
        ?InvoiceTypeListItem $invoiceTypeListItem,
        ?InvoiceStatusListItem $invoiceStatusListItem
    ) : OrderInvoice
    {
        try {
            $orderInvoice->update([
                'parent_id' => $parentInvoice ? $parentInvoice->id : $orderInvoice->parent_id,
                'order_id'  => $order ? $order->id : $orderInvoice->order_id,
                'type_id'   => $invoiceTypeListItem ? $invoiceTypeListItem->id : $orderInvoice->type_id,
                'status_id' => $invoiceStatusListItem ? $invoiceStatusListItem->id : $orderInvoice->status_id
            ]);

            return $orderInvoice;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderInvoice $orderInvoice
     * @param InvoiceStatusListItem $invoiceStatusListItem
     *
     * @return OrderInvoice
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        OrderInvoice $orderInvoice,
        InvoiceStatusListItem $invoiceStatusListItem
    ) : OrderInvoice
    {
        try {
            $orderInvoice->update([
                'status_id' => $invoiceStatusListItem->id
            ]);

            return $orderInvoice;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderInvoice $orderInvoice
     * @param OrderItem $orderItem
     *
     * @throws DatabaseException
     */
    public function attachOrderItem(
        OrderInvoice $orderInvoice,
        OrderItem $orderItem
    ) : void
    {
        try {
            $orderInvoice->items()->sync([
                $orderItem->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderInvoice $orderInvoice
     * @param array $orderItemsIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachOrderItems(
        OrderInvoice $orderInvoice,
        array $orderItemsIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $orderInvoice->items()->sync(
                $orderItemsIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderInvoice $orderInvoice
     * @param OrderItem $orderItem
     *
     * @throws DatabaseException
     */
    public function detachOrderItem(
        OrderInvoice $orderInvoice,
        OrderItem $orderItem
    ) : void
    {
        try {
            $orderInvoice->items()->detach([
                $orderItem->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderInvoice $orderInvoice
     * @param array $orderItemsIds
     *
     * @throws DatabaseException
     */
    public function detachOrderItems(
        OrderInvoice $orderInvoice,
        array $orderItemsIds
    ) : void
    {
        try {
            $orderInvoice->items()->detach(
                $orderItemsIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderInvoice $orderInvoice
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        OrderInvoice $orderInvoice
    ) : bool
    {
        try {
            return $orderInvoice->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/order/orderInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
