<?php

namespace App\Repositories\Tip;

use App\Exceptions\DatabaseException;
use App\Lists\Invoice\Status\InvoiceStatusListItem;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Lists\Invoice\Type\InvoiceTypeListItem;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Tip\Tip;
use App\Models\MySql\Tip\TipInvoice;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Tip\Interfaces\TipInvoiceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class TipInvoiceRepository
 *
 * @package App\Repositories\Tip
 */
class TipInvoiceRepository extends BaseRepository implements TipInvoiceRepositoryInterface
{
    /**
     * OrderRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.tipInvoice.cacheTime');
        $this->perPage = config('repositories.tipInvoice.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return TipInvoice|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?TipInvoice
    {
        try {
            return TipInvoice::query()
                ->with([
                    'tip'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return TipInvoice|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?int $id
    ) : ?TipInvoice
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->with([
                            'item' => function ($query) {
                                $query->with([
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
                                ])
                                ->with([
                                    'billing' => function ($query) {
                                        $query->with([
                                            'countryPlace',
                                            'regionPlace'
                                        ]);
                                    }
                                ]);
                            },
                            'seller' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ])
                                ->with([
                                    'billing' => function ($query) {
                                        $query->with([
                                            'countryPlace',
                                            'regionPlace'
                                        ]);
                                    }
                                ]);
                            },
                            'transactions' => function ($query) {
                                $query->select([
                                    'id',
                                    'tip_id',
                                    'external_id',
                                    'method_id',
                                    'amount',
                                    'transaction_fee',
                                    'description',
                                    'created_at'
                                ])->with([
                                    'method' => function ($query) {
                                        $query->select([
                                            'id',
                                            'name',
                                            'code'
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
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
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
            return Cache::remember('tipInvoices.buyers.all.count', $this->cacheTime,
                function () {
                    return TipInvoice::query()
                        ->where('type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoices.' . __FUNCTION__),
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
            return Cache::remember('tipInvoices.sellers.all.count', config('repositories.tipInvoice.cacheTime.cacheTime'),
                function () {
                    return TipInvoice::query()
                        ->where('type_id', '=', InvoiceTypeList::getTipSeller()->id)
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoices.' . __FUNCTION__),
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
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->with([
                            'item' => function ($query) {
                                $query->with([
                                    'appearanceCase' => function ($query) {
                                        $query->select([
                                            'id',
                                            'vybe_id',
                                            'appearance_id',
                                            'unit_id',
                                            'price'
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
                                    'timeslot' => function ($query) {
                                        $query->select([
                                            'id',
                                            'datetime_from',
                                            'datetime_to'
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
                            },
                            'buyer' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username',
                                    'first_name',
                                    'last_name'
                                ]);
                            },
                            'seller' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username',
                                    'first_name',
                                    'last_name'
                                ]);
                            }
                        ]);
                    }
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
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
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->with([
                            'item' => function ($query) {
                                $query->with([
                                    'appearanceCase' => function ($query) {
                                        $query->select([
                                            'id',
                                            'vybe_id',
                                            'appearance_id',
                                            'unit_id',
                                            'price'
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
                                                                        }]
                                                                );
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
                                    'username',
                                    'first_name',
                                    'last_name'
                                ]);
                            },
                            'seller' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username',
                                    'first_name',
                                    'last_name'
                                ]);
                            }
                        ]);
                    }
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        InvoiceTypeListItem $invoiceTypeListItem,
        ?int $orderItemId = null,
        ?int $vybeTypeId = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?array $orderItemStatusesIds = null,
        ?int $tipInvoiceId = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->with([
                            'item' => function ($query) {
                                $query->with([
                                    'vybe' => function ($query) {
                                        $query->select([
                                            'id',
                                            'user_id',
                                            'type_id'
                                        ]);
                                    },
                                    'appearanceCase' => function ($query) {
                                        $query->select([
                                            'id',
                                            'vybe_id',
                                            'appearance_id',
                                            'unit_id',
                                            'price'
                                        ])->with([
                                            'vybe' => function ($query) {
                                                $query->select([
                                                    'id',
                                                    'user_id',
                                                    'type_id'
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
                                ]);
                            },
                            'seller' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypeId, function ($query) use ($vybeTypeId) {
                    $query->whereHas('tip.item.appearanceCase.vybe', function ($query) use ($vybeTypeId) {
                        $query->where('type_id', '=', $vybeTypeId);
                    });
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('tip.buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('tip.seller', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->whereHas('tip', function($query) use ($dateFrom) {
                        $query->where('created_at', '>=', Carbon::parse($dateFrom));
                    });
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->whereHas('tip', function($query) use ($dateTo) {
                        $query->where('created_at', '<=', Carbon::parse($dateTo));
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($tipInvoiceId, function ($query) use ($tipInvoiceId) {
                    $query->where('id', '=', $tipInvoiceId);
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy(
                            Tip::query()->select('item_id')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('tips', 'tips.item_id', '=', 'order_items.id')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('tips', 'tips.buyer_id', '=', 'users.id')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('tips', 'tips.seller_id', '=', 'users.id')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy(
                            OrderItem::query()->select('status_id')
                                ->join('tips', 'tips.item_id', '=', 'order_items.id')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'tip_amount') {
                        $query->orderBy(
                            Tip::query()->select('amount')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->where('type_id', '=', $invoiceTypeListItem->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllPaginatedFiltered(
        InvoiceTypeListItem $invoiceTypeListItem,
        ?int $orderItemId = null,
        ?int $vybeTypeId = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?array $orderItemStatusesIds = null,
        ?int $tipInvoiceId = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {

        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->with([
                            'item' => function ($query) {
                                $query->with([
                                    'appearanceCase' => function ($query) {
                                        $query->select([
                                            'id',
                                            'vybe_id',
                                            'appearance_id',
                                            'unit_id',
                                            'price'
                                        ])->with([
                                            'vybe' => function ($query) {
                                                $query->select([
                                                    'id',
                                                    'user_id',
                                                    'type_id'
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
                                ]);
                            },
                            'seller' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypeId, function ($query) use ($vybeTypeId) {
                    $query->whereHas('item.appearanceCase.vybe', function ($query) use ($vybeTypeId) {
                        $query->where('type_id', '=', $vybeTypeId);
                    });
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('seller', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($tipInvoiceId, function ($query) use ($tipInvoiceId) {
                    $query->where('id', '=', $tipInvoiceId);
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy(
                            Tip::query()->select('item_id')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('order_items.id', 'tips.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('users.id', 'tips.buyer_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('users.id', 'tips.seller_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy(
                            OrderItem::query()->select('status_id')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('order_items.id', 'tips.item_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'tip_amount') {
                        $query->orderBy(
                            Tip::query()->select('amount')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->where('type_id', '=', $invoiceTypeListItem->id)
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForAdminLabels(
        InvoiceTypeListItem $invoiceTypeListItem,
        ?int $orderItemId = null,
        ?int $vybeTypeId = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $tipInvoiceId = null
    ) : Collection
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->select([
                            'id',
                            'item_id'
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypeId, function ($query) use ($vybeTypeId) {
                    $query->whereHas('tip.item.appearanceCase.vybe', function ($query) use ($vybeTypeId) {
                        $query->where('type_id', '=', $vybeTypeId);
                    });
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('tip.buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('tip.seller', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->whereHas('tip', function ($query) use ($dateFrom) {
                        $query->where('created_at', '>=', Carbon::parse($dateFrom));
                    });
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->whereHas('tip', function ($query) use ($dateTo) {
                        $query->where('created_at', '<=', Carbon::parse($dateTo));
                    });
                })
                ->when($tipInvoiceId, function ($query) use ($tipInvoiceId) {
                    $query->where('id', '=', $tipInvoiceId);
                })
                ->where('type_id', '=', $invoiceTypeListItem->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForBuyer(
        User $buyer,
        ?int $orderItemId = null,
        ?int $vybeTypeId = null,
        ?string $seller = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?array $orderItemStatusesIds = null,
        ?int $tipInvoiceId = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->with([
                            'item' => function ($query) {
                                $query->with([
                                    'appearanceCase' => function ($query) {
                                        $query->select([
                                            'id',
                                            'vybe_id',
                                            'appearance_id',
                                            'unit_id',
                                            'price'
                                        ])->with([
                                            'vybe' => function ($query) {
                                                $query->select([
                                                    'id',
                                                    'user_id',
                                                    'type_id'
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
                                ]);
                            },
                            'seller' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypeId, function ($query) use ($vybeTypeId) {
                    $query->whereHas('tip.item.appearanceCase.vybe', function ($query) use ($vybeTypeId) {
                        $query->where('type_id', '=', $vybeTypeId);
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('tip.seller', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($tipInvoiceId, function ($query) use ($tipInvoiceId) {
                    $query->where('id', '=', $tipInvoiceId);
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy(
                            Tip::query()->select('item_id')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('order_items.id', 'tips.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('users.id', 'tips.seller_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy(
                            OrderItem::query()->select('status_id')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('order_items.id', 'tips.item_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'tip_amount') {
                        $query->orderBy(
                            Tip::query()->select('amount')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->where('type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                ->whereHas('tip', function ($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllPaginatedFilteredForBuyer(
        User $buyer,
        ?int $orderItemId = null,
        ?int $vybeTypeId = null,
        ?string $seller = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?array $orderItemStatusesIds = null,
        ?int $tipInvoiceId = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->with([
                            'item' => function ($query) {
                                $query->with([
                                    'appearanceCase' => function ($query) {
                                        $query->select([
                                            'id',
                                            'vybe_id',
                                            'appearance_id',
                                            'unit_id',
                                            'price'
                                        ])->with([
                                            'vybe' => function ($query) {
                                                $query->select([
                                                    'id',
                                                    'user_id',
                                                    'type_id'
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
                                ]);
                            },
                            'seller' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypeId, function ($query) use ($vybeTypeId) {
                    $query->whereHas('tip.item.appearanceCase.vybe', function ($query) use ($vybeTypeId) {
                        $query->where('type_id', '=', $vybeTypeId);
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('tip.seller', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($tipInvoiceId, function ($query) use ($tipInvoiceId) {
                    $query->where('id', '=', $tipInvoiceId);
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy(
                            Tip::query()->select('item_id')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('order_items.id', 'tips.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('users.id', 'tips.seller_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy(
                            OrderItem::query()->select('status_id')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('order_items.id', 'tips.item_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'tip_amount') {
                        $query->orderBy(
                            Tip::query()->select('amount')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->where('type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                ->whereHas('tip', function ($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $buyer
     * @param int|null $orderItemId
     * @param int|null $vybeTypeId
     * @param string|null $seller
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $tipInvoiceId
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForBuyerForAdminLabels(
        User $buyer,
        ?int $orderItemId = null,
        ?int $vybeTypeId = null,
        ?string $seller = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $tipInvoiceId = null
    ) : Collection
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->select([
                            'id',
                            'item_id'
                        ])->with([
                            'item' => function ($query) {
                                $query->select([
                                    'id',
                                    'order_id',
                                    'vybe_id'
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypeId, function ($query) use ($vybeTypeId) {
                    $query->whereHas('tip.item.appearanceCase.vybe', function ($query) use ($vybeTypeId) {
                        $query->where('type_id', '=', $vybeTypeId);
                    });
                })
                ->when($seller, function ($query) use ($seller) {
                    $query->whereHas('tip.seller', function ($query) use ($seller) {
                        $query->where('username', 'LIKE', '%'. $seller .'%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($tipInvoiceId, function ($query) use ($tipInvoiceId) {
                    $query->where('id', '=', $tipInvoiceId);
                })
                ->where('type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                ->whereHas('tip', function ($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForSeller(
        User $seller,
        ?int $orderItemId = null,
        ?int $vybeTypeId = null,
        ?string $buyer = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?array $orderItemStatusesIds = null,
        ?int $tipInvoiceId = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->with([
                            'item' => function ($query) {
                                $query->with([
                                    'appearanceCase' => function ($query) {
                                        $query->select([
                                            'id',
                                            'vybe_id',
                                            'appearance_id',
                                            'unit_id',
                                            'price'
                                        ])->with([
                                            'vybe' => function ($query) {
                                                $query->select([
                                                    'id',
                                                    'user_id',
                                                    'type_id'
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
                                ]);
                            },
                            'seller' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypeId, function ($query) use ($vybeTypeId) {
                    $query->whereHas('tip.item.appearanceCase.vybe', function ($query) use ($vybeTypeId) {
                        $query->where('type_id', '=', $vybeTypeId);
                    });
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('tip.buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($tipInvoiceId, function ($query) use ($tipInvoiceId) {
                    $query->where('id', '=', $tipInvoiceId);
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy(
                            Tip::query()->select('item_id')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('order_items.id', 'tips.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('users.id', 'tips.buyer_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy(
                            OrderItem::query()->select('status_id')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('order_items.id', 'tips.item_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'tip_amount') {
                        $query->orderBy(
                            Tip::query()->select('amount')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->where('type_id', '=', InvoiceTypeList::getTipSeller()->id)
                ->whereHas('tip', function ($query) use ($seller) {
                    $query->where('seller_id', '=', $seller->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getAllPaginatedFilteredForSeller(
        User $seller,
        ?int $orderItemId = null,
        ?int $vybeTypeId = null,
        ?string $buyer = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?array $orderItemStatusesIds = null,
        ?int $tipInvoiceId = null,
        ?array $invoiceStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->with([
                            'item' => function ($query) {
                                $query->with([
                                    'appearanceCase' => function ($query) {
                                        $query->select([
                                            'id',
                                            'vybe_id',
                                            'appearance_id',
                                            'unit_id',
                                            'price'
                                        ])->with([
                                            'vybe' => function ($query) {
                                                $query->select([
                                                    'id',
                                                    'user_id',
                                                    'type_id'
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
                                ]);
                            },
                            'seller' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypeId, function ($query) use ($vybeTypeId) {
                    $query->whereHas('tip.item.appearanceCase.vybe', function ($query) use ($vybeTypeId) {
                        $query->where('type_id', '=', $vybeTypeId);
                    });
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('tip.buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($tipInvoiceId, function ($query) use ($tipInvoiceId) {
                    $query->where('id', '=', $tipInvoiceId);
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy(
                            Tip::query()->select('item_id')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('order_items.id', 'tips.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('users.id', 'tips.buyer_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy(
                            OrderItem::query()->select('status_id')
                                ->join('tips', 'tips.id', '=', 'tip_invoices.tip_id')
                                ->whereColumn('order_items.id', 'tips.item_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_invoice_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'tip_amount') {
                        $query->orderBy(
                            Tip::query()->select('amount')
                                ->whereColumn('tips.id', 'tip_invoices.tip_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->where('type_id', '=', InvoiceTypeList::getTipSeller()->id)
                ->whereHas('tip', function ($query) use ($seller) {
                    $query->where('seller_id', '=', $seller->id);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $seller
     * @param int|null $orderItemId
     * @param int|null $vybeTypeId
     * @param string|null $buyer
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $tipInvoiceId
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForSellerForAdminLabels(
        User $seller,
        ?int $orderItemId = null,
        ?int $vybeTypeId = null,
        ?string $buyer = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $tipInvoiceId = null
    ) : Collection
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->select([
                            'id',
                            'item_id'
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypeId, function ($query) use ($vybeTypeId) {
                    $query->whereHas('tip.item.appearanceCase.vybe', function ($query) use ($vybeTypeId) {
                        $query->where('type_id', '=', $vybeTypeId);
                    });
                })
                ->when($buyer, function ($query) use ($buyer) {
                    $query->whereHas('tip.buyer', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($tipInvoiceId, function ($query) use ($tipInvoiceId) {
                    $query->where('id', '=', $tipInvoiceId);
                })
                ->where('type_id', '=', InvoiceTypeList::getTipSeller()->id)
                ->whereHas('tip', function ($query) use ($seller) {
                    $query->where('seller_id', '=', $seller->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param InvoiceTypeListItem $invoiceTypeListItem
     * @param array $tipInvoicesIds
     *
     * @return TipInvoice
     *
     * @throws DatabaseException
     */
    public function getStatusesByIdsCount(
        InvoiceTypeListItem $invoiceTypeListItem,
        array $tipInvoicesIds
    ) : TipInvoice
    {
        try {
            return TipInvoice::query()
                ->whereIn('id', $tipInvoicesIds)
                ->where('type_id', '=', $invoiceTypeListItem->id)
                ->selectRaw('sum(case when status_id = 1 then 1 else 0 end) as unpaid')
                ->selectRaw('sum(case when status_id = 2 then 1 else 0 end) as on_hold')
                ->selectRaw('sum(case when status_id = 3 then 1 else 0 end) as pending_payout')
                ->selectRaw('sum(case when status_id = 4 then 1 else 0 end) as paid')
                ->selectRaw('sum(case when status_id = 5 then 1 else 0 end) as canceled')
                ->selectRaw('sum(case when status_id = 6 then 1 else 0 end) as credit')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getForDashboardBuyerFiltered(
        User $buyer,
        ?int $orderItemId = null,
        ?int $invoiceId = null,
        ?string $username = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $amount = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $invoiceStatusesIds = null
    ) : Collection
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->select([
                            'id',
                            'item_id',
                            'buyer_id',
                            'seller_id',
                            'amount',
                            'amount_earned',
                            'paid_at',
                            'created_at'
                        ])->with([
                            'item' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'status_id'
                                ])->with([
                                    'vybe' => function ($query) {
                                        $query->select([
                                            'id',
                                            'type_id',
                                            'title'
                                        ]);
                                    }
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
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($invoiceId, function ($query) use ($invoiceId) {
                    $query->where('id', '=', $invoiceId);
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('tip.seller', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username .'%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($amount, function ($query) use ($amount) {
                    $query->whereHas('tip', function ($query) use ($amount) {
                        $query->where('amount', '=', $amount);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('tip.item.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('status_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->where('type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                ->whereHas('tip', function ($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getForDashboardBuyerFilteredPaginated(
        User $buyer,
        ?int $orderItemId = null,
        ?int $invoiceId = null,
        ?string $username = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $amount = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->select([
                            'id',
                            'item_id',
                            'buyer_id',
                            'seller_id',
                            'amount',
                            'amount_earned',
                            'paid_at',
                            'created_at'
                        ])->with([
                            'item' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'status_id'
                                ])->with([
                                    'vybe' => function ($query) {
                                        $query->select([
                                            'id',
                                            'type_id',
                                            'title'
                                        ]);
                                    }
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
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($invoiceId, function ($query) use ($invoiceId) {
                    $query->where('id', '=', $invoiceId);
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('tip.seller', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username .'%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($amount, function ($query) use ($amount) {
                    $query->whereHas('tip', function ($query) use ($amount) {
                        $query->where('amount', '=', $amount);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('tip.item.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('status_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->where('type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                ->whereHas('tip', function ($query) use ($buyer) {
                    $query->where('buyer_id', '=', $buyer->id);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getForDashboardSellerFiltered(
        User $seller,
        ?int $orderItemId = null,
        ?int $invoiceId = null,
        ?string $username = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $amount = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $invoiceStatusesIds = null
    ) : Collection
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->select([
                            'id',
                            'item_id',
                            'buyer_id',
                            'seller_id',
                            'amount',
                            'amount_earned',
                            'paid_at',
                            'created_at'
                        ])->with([
                            'item' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'status_id'
                                ])->with([
                                    'vybe' => function ($query) {
                                        $query->select([
                                            'id',
                                            'type_id',
                                            'title'
                                        ]);
                                    }
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
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($invoiceId, function ($query) use ($invoiceId) {
                    $query->where('id', '=', $invoiceId);
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('tip.seller', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username .'%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($amount, function ($query) use ($amount) {
                    $query->whereHas('tip', function ($query) use ($amount) {
                        $query->where('amount', '=', $amount);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('tip.item.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('status_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->where('type_id', '=', InvoiceTypeList::getTipSeller()->id)
                ->whereHas('tip', function ($query) use ($seller) {
                    $query->where('seller_id', '=', $seller->id);
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
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
     *
     * @throws DatabaseException
     */
    public function getForDashboardSellerFilteredPaginated(
        User $seller,
        ?int $orderItemId = null,
        ?int $invoiceId = null,
        ?string $username = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $amount = null,
        ?array $vybeTypesIds = null,
        ?array $orderItemStatusesIds = null,
        ?array $invoiceStatusesIds = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return TipInvoice::query()
                ->select([
                    'id',
                    'tip_id',
                    'type_id',
                    'status_id',
                    'created_at'
                ])
                ->with([
                    'tip' => function ($query) {
                        $query->select([
                            'id',
                            'item_id',
                            'buyer_id',
                            'seller_id',
                            'amount',
                            'amount_earned',
                            'paid_at',
                            'created_at'
                        ])->with([
                            'item' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'status_id'
                                ])->with([
                                    'vybe' => function ($query) {
                                        $query->select([
                                            'id',
                                            'type_id',
                                            'title'
                                        ]);
                                    }
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
                        ]);
                    }
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($invoiceId, function ($query) use ($invoiceId) {
                    $query->where('id', '=', $invoiceId);
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('tip.seller', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username .'%');
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($amount, function ($query) use ($amount) {
                    $query->whereHas('tip', function ($query) use ($amount) {
                        $query->where('amount', '=', $amount);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('tip.item.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('status_id', $vybeTypesIds);
                    });
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('tip.item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($invoiceStatusesIds, function ($query) use ($invoiceStatusesIds) {
                    $query->whereIn('status_id', $invoiceStatusesIds);
                })
                ->where('type_id', '=', InvoiceTypeList::getTipSeller()->id)
                ->whereHas('tip', function ($query) use ($seller) {
                    $query->where('seller_id', '=', $seller->id);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Tip $tip
     * @param InvoiceTypeListItem $invoiceTypeListItem
     * @param InvoiceStatusListItem $invoiceStatusListItem
     *
     * @return TipInvoice|null
     *
     * @throws DatabaseException
     */
    public function store(
        Tip $tip,
        InvoiceTypeListItem $invoiceTypeListItem,
        InvoiceStatusListItem $invoiceStatusListItem
    ) : ?TipInvoice
    {

        try {
            return TipInvoice::query()->create([
                'tip_id'    => $tip->id,
                'type_id'   => $invoiceTypeListItem->id,
                'status_id' => $invoiceStatusListItem->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TipInvoice $tipInvoice
     * @param Tip $tip
     * @param InvoiceTypeListItem|null $invoiceTypeListItem
     * @param InvoiceStatusListItem|null $invoiceStatusListItem
     *
     * @return TipInvoice
     *
     * @throws DatabaseException
     */
    public function update(
        TipInvoice $tipInvoice,
        Tip $tip,
        ?InvoiceTypeListItem $invoiceTypeListItem,
        ?InvoiceStatusListItem $invoiceStatusListItem
    ) : TipInvoice
    {
        try {
            $tipInvoice->update([
                'tip_id'    => $tip->id,
                'type_id'   => $invoiceTypeListItem ? $invoiceTypeListItem->id : $tipInvoice->type_id,
                'status_id' => $invoiceStatusListItem ? $invoiceStatusListItem->id : $tipInvoice->status_id
            ]);

            return $tipInvoice;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TipInvoice $tipInvoice
     * @param InvoiceStatusListItem $invoiceStatusListItem
     *
     * @return TipInvoice
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        TipInvoice $tipInvoice,
        InvoiceStatusListItem $invoiceStatusListItem
    ) : TipInvoice
    {
        try {
            $tipInvoice->update([
                'status_id' => $invoiceStatusListItem->id
            ]);

            return $tipInvoice;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TipInvoice $tipInvoice
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        TipInvoice $tipInvoice
    ) : bool
    {
        try {
            return $tipInvoice->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tipInvoice.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
