<?php

namespace App\Repositories\Tip;

use App\Exceptions\DatabaseException;
use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Tip\Tip;
use App\Models\MySql\Tip\TipInvoice;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Tip\Interfaces\TipRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class TipRepository
 *
 * @package App\Repositories\Tip
 */
class TipRepository extends BaseRepository implements TipRepositoryInterface
{
    /**
     * TipRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.tip.cacheTime');
        $this->perPage = config('repositories.tip.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Tip|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Tip
    {
        try {
            return Tip::query()
                ->with([
                    'item',
                    'seller',
                    'buyer',
                    'method'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return Tip|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?int $id
    ) : ?Tip
    {
        try {
            return Tip::query()
                ->with([
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
                    },
                    'invoices'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
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
            return Cache::remember('tips.all.count', $this->cacheTime,
                function () {
                    return Tip::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
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
            return Tip::query()
                ->with([
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
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
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
            return Tip::query()
                ->with([
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
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $orderItemId
     * @param array|null $vybeTypesIds
     * @param string|null $buyer
     * @param string|null $seller
     * @param array|null $paymentMethodsIds
     * @param array|null $orderItemStatusesIds
     * @param int|null $tipInvoiceBuyerId
     * @param array|null $tipInvoiceBuyerStatusesIds
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $tipInvoiceSellerId
     * @param array|null $tipInvoiceSellerStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        ?int $orderItemId = null,
        ?array $vybeTypesIds = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?array $paymentMethodsIds = null,
        ?array $orderItemStatusesIds = null,
        ?int $tipInvoiceBuyerId = null,
        ?array $tipInvoiceBuyerStatusesIds = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $tipInvoiceSellerId = null,
        ?array $tipInvoiceSellerStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return Tip::query()
                ->with([
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
                    },
                    'invoices' => function ($query) {
                        $query->select([
                            'id',
                            'tip_id',
                            'type_id',
                            'status_id'
                        ]);
                    },
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('item.appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
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
                ->when($paymentMethodsIds, function ($query) use ($paymentMethodsIds) {
                    $query->whereIn('method_id', $paymentMethodsIds);
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($tipInvoiceBuyerId, function ($query) use ($tipInvoiceBuyerId) {
                    $query->whereHas('invoices', function ($query) use ($tipInvoiceBuyerId) {
                        $query
                            ->where('type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                            ->where('id', '=', $tipInvoiceBuyerId);
                    });
                })
                ->when($tipInvoiceBuyerStatusesIds, function ($query) use ($tipInvoiceBuyerStatusesIds) {
                    $query->whereHas('invoices', function ($query) use ($tipInvoiceBuyerStatusesIds) {
                        $query
                            ->where('type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                            ->whereIn('status_id', $tipInvoiceBuyerStatusesIds);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($tipInvoiceSellerId, function ($query) use ($tipInvoiceSellerId) {
                    $query->whereHas('invoices', function ($query) use ($tipInvoiceSellerId) {
                        $query
                            ->where('type_id', '=', InvoiceTypeList::getTipSeller()->id)
                            ->where('id', '=', $tipInvoiceSellerId);
                    });
                })
                ->when($tipInvoiceSellerStatusesIds, function ($query) use ($tipInvoiceSellerStatusesIds) {
                    $query->whereHas('invoices', function ($query) use ($tipInvoiceSellerStatusesIds) {
                        $query
                            ->where('type_id', '=', InvoiceTypeList::getTipSeller()->id)
                            ->whereIn('status_id', $tipInvoiceSellerStatusesIds);
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy('item_id', $sortOrder);
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->whereColumn('order_items.id', 'tips.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy('buyer_id', $sortOrder);
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy('seller_id', $sortOrder);
                    }

                    if ($sortBy == 'payment_method') {
                        $query->orderBy('method_id', $sortOrder);
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy(
                            OrderItem::query()->select('status_id')
                                ->whereColumn('order_items.id', 'tips.item_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_invoice_buyer_id') {
                        $query->orderBy(
                            TipInvoice::query()->select('id')
                                ->where('tip_invoices.type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                                ->whereColumn('tip_invoices.id', 'tips.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_invoice_buyer_status') {
                        $query->orderBy(
                            TipInvoice::query()->select('status_id')
                                ->where('tip_invoices.type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                                ->whereColumn('tip_invoices.id', 'tips.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_amount') {
                        $query->orderBy('amount', $sortOrder);
                    }

                    if ($sortBy == 'handling_fee') {
                        $query->orderBy('handling_fee', $sortOrder);
                    }

                    if ($sortBy == 'tip_invoice_seller_id') {
                        $query->orderBy(
                            TipInvoice::query()->select('id')
                                ->where('tip_invoices.type_id', '=', InvoiceTypeList::getTipSeller()->id)
                                ->whereColumn('tip_invoices.id', 'tips.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_invoice_seller_status') {
                        $query->orderBy(
                            TipInvoice::query()->select('status_id')
                                ->where('tip_invoices.type_id', '=', InvoiceTypeList::getTipSeller()->id)
                                ->whereColumn('tip_invoices.id', 'tips.id'),
                            $sortOrder
                        );
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $orderItemId
     * @param array|null $vybeTypesIds
     * @param string|null $buyer
     * @param string|null $seller
     * @param array|null $paymentMethodsIds
     * @param array|null $orderItemStatusesIds
     * @param int|null $tipInvoiceBuyerId
     * @param array|null $tipInvoiceBuyerStatusesIds
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $tipInvoiceSellerId
     * @param array|null $tipInvoiceSellerStatusesIds
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
        ?array $vybeTypesIds = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?array $paymentMethodsIds = null,
        ?array $orderItemStatusesIds = null,
        ?int $tipInvoiceBuyerId = null,
        ?array $tipInvoiceBuyerStatusesIds = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $tipInvoiceSellerId = null,
        ?array $tipInvoiceSellerStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Tip::query()
                ->with([
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
                    },
                    'invoices' => function ($query) {
                        $query->select([
                            'id',
                            'tip_id',
                            'type_id',
                            'status_id'
                        ]);
                    },
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
                    });
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereHas('item.appearanceCase.vybe', function ($query) use ($vybeTypesIds) {
                        $query->whereIn('type_id', $vybeTypesIds);
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
                ->when($paymentMethodsIds, function ($query) use ($paymentMethodsIds) {
                    $query->whereIn('method_id', $paymentMethodsIds);
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($tipInvoiceBuyerId, function ($query) use ($tipInvoiceBuyerId) {
                    $query->whereHas('invoices', function ($query) use ($tipInvoiceBuyerId) {
                        $query
                            ->where('type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                            ->where('id', '=', $tipInvoiceBuyerId);
                    });
                })
                ->when($tipInvoiceBuyerStatusesIds, function ($query) use ($tipInvoiceBuyerStatusesIds) {
                    $query->whereHas('invoices', function ($query) use ($tipInvoiceBuyerStatusesIds) {
                        $query
                            ->where('type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                            ->whereIn('status_id', $tipInvoiceBuyerStatusesIds);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($tipInvoiceSellerId, function ($query) use ($tipInvoiceSellerId) {
                    $query->whereHas('invoices', function ($query) use ($tipInvoiceSellerId) {
                        $query
                            ->where('type_id', '=', InvoiceTypeList::getTipSeller()->id)
                            ->where('id', '=', $tipInvoiceSellerId);
                    });
                })
                ->when($tipInvoiceSellerStatusesIds, function ($query) use ($tipInvoiceSellerStatusesIds) {
                    $query->whereHas('invoices', function ($query) use ($tipInvoiceSellerStatusesIds) {
                        $query
                            ->where('type_id', '=', InvoiceTypeList::getTipSeller()->id)
                            ->whereIn('status_id', $tipInvoiceSellerStatusesIds);
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'order_item_id') {
                        $query->orderBy('item_id', $sortOrder);
                    }

                    if ($sortBy == 'vybe_type') {
                        $query->orderBy(
                            Vybe::query()->select('type_id')
                                ->join('appearance_cases', 'appearance_cases.vybe_id', '=', 'vybes.id')
                                ->join('order_items', 'order_items.appearance_case_id', '=', 'appearance_cases.id')
                                ->whereColumn('order_items.id', 'tips.item_id')
                                ->latest('order_items.id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy('buyer_id', $sortOrder);
                    }

                    if ($sortBy == 'seller') {
                        $query->orderBy('seller_id', $sortOrder);
                    }

                    if ($sortBy == 'payment_method') {
                        $query->orderBy('method_id', $sortOrder);
                    }

                    if ($sortBy == 'order_item_status') {
                        $query->orderBy(
                            OrderItem::query()->select('status_id')
                                ->whereColumn('order_items.id', 'tips.item_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_invoice_buyer_id') {
                        $query->orderBy(
                            TipInvoice::query()->select('id')
                                ->where('tip_invoices.type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                                ->whereColumn('tip_invoices.id', 'tips.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_invoice_buyer_status') {
                        $query->orderBy(
                            TipInvoice::query()->select('status_id')
                                ->where('tip_invoices.type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                                ->whereColumn('tip_invoices.id', 'tips.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_amount') {
                        $query->orderBy('amount', $sortOrder);
                    }

                    if ($sortBy == 'handling_fee') {
                        $query->orderBy('handling_fee', $sortOrder);
                    }

                    if ($sortBy == 'tip_invoice_seller_id') {
                        $query->orderBy(
                            TipInvoice::query()->select('id')
                                ->where('tip_invoices.type_id', '=', InvoiceTypeList::getTipSeller()->id)
                                ->whereColumn('tip_invoices.id', 'tips.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'tip_invoice_seller_status') {
                        $query->orderBy(
                            TipInvoice::query()->select('status_id')
                                ->where('tip_invoices.type_id', '=', InvoiceTypeList::getTipSeller()->id)
                                ->whereColumn('tip_invoices.id', 'tips.id'),
                            $sortOrder
                        );
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $orderItemId
     * @param string|null $buyer
     * @param string|null $seller
     * @param array|null $paymentMethodsIds
     * @param array|null $orderItemStatusesIds
     * @param int|null $tipInvoiceBuyerId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $tipInvoiceSellerId
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForAdminLabels(
        ?int $orderItemId = null,
        ?string $buyer = null,
        ?string $seller = null,
        ?array $paymentMethodsIds = null,
        ?array $orderItemStatusesIds = null,
        ?int $tipInvoiceBuyerId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $tipInvoiceSellerId = null
    ) : Collection
    {
        try {
            return Tip::query()
                ->select([
                    'id',
                    'item_id'
                ])
                ->with([
                    'item' => function ($query) {
                        $query->select([
                            'id',
                            'order_id',
                            'vybe_id'
                        ]);
                    },
                    'invoices' => function ($query) {
                        $query->select([
                            'id',
                            'tip_id',
                            'type_id'
                        ]);
                    },
                ])
                ->when($orderItemId, function ($query) use ($orderItemId) {
                    $query->whereHas('item', function ($query) use ($orderItemId) {
                        $query->where('id', '=', $orderItemId);
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
                ->when($paymentMethodsIds, function ($query) use ($paymentMethodsIds) {
                    $query->whereIn('method_id', $paymentMethodsIds);
                })
                ->when($orderItemStatusesIds, function ($query) use ($orderItemStatusesIds) {
                    $query->whereHas('item', function ($query) use ($orderItemStatusesIds) {
                        $query->whereIn('status_id', $orderItemStatusesIds);
                    });
                })
                ->when($tipInvoiceBuyerId, function ($query) use ($tipInvoiceBuyerId) {
                    $query->whereHas('invoices', function ($query) use ($tipInvoiceBuyerId) {
                        $query
                            ->where('type_id', '=', InvoiceTypeList::getTipBuyer()->id)
                            ->where('id', '=', $tipInvoiceBuyerId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($tipInvoiceSellerId, function ($query) use ($tipInvoiceSellerId) {
                    $query->whereHas('invoices', function ($query) use ($tipInvoiceSellerId) {
                        $query
                            ->where('type_id', '=', InvoiceTypeList::getTipSeller()->id)
                            ->where('id', '=', $tipInvoiceSellerId);
                    });
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $buyer
     * @param OrderItem $orderItem
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsPaidForBuyer(
        User $buyer,
        OrderItem $orderItem
    ) : bool
    {
        try {
            return Tip::query()
                ->where('buyer_id', '=', $buyer->id)
                ->where('item_id', '=', $orderItem->id)
                ->whereHas('invoices', function ($query) {
                    $query->where('status_id', '=', InvoiceStatusList::getPaid()->id);
                })
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderItem $orderItem
     * @param PaymentMethod $paymentMethod
     * @param User $buyer
     * @param User $seller
     * @param float $amount
     * @param float $amountEarned
     * @param float|null $amountTax
     * @param float|null $amountTotal
     * @param float|null $handlingFee
     * @param float|null $paymentFee
     * @param float|null $paymentFeeTax
     * @param string|null $comment
     * @param string|null $paidAt
     *
     * @return Tip|null
     *
     * @throws DatabaseException
     */
    public function store(
        OrderItem $orderItem,
        PaymentMethod $paymentMethod,
        User $buyer,
        User $seller,
        float $amount,
        float $amountEarned,
        ?float $amountTax,
        ?float $amountTotal,
        ?float $handlingFee = 0,
        ?float $paymentFee = 0,
        ?float $paymentFeeTax = 0,
        ?string $comment = null,
        ?string $paidAt = null
    ) : ?Tip
    {

        try {
            return Tip::query()->create([
                'item_id'         => $orderItem->id,
                'method_id'       => $paymentMethod->id,
                'buyer_id'        => $buyer->id,
                'seller_id'       => $seller->id,
                'amount'          => $amount,
                'amount_earned'   => $amountEarned,
                'amount_tax'      => $amountTax,
                'amount_total'    => $amountTotal,
                'handling_fee'    => $handlingFee,
                'payment_fee'     => $paymentFee,
                'payment_fee_tax' => $paymentFeeTax,
                'comment'         => $comment,
                'hash'            => generatePaymentHash(),
                'paid_at'         => $paidAt ?
                    Carbon::parse($paidAt)->format('Y-m-d H:i:s') :
                    null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Tip $tip
     * @param OrderItem|null $orderItem
     * @param PaymentMethod|null $paymentMethod
     * @param User|null $buyer
     * @param User|null $seller
     * @param float|null $amount
     * @param float|null $amountEarned
     * @param float|null $amountTax
     * @param float|null $amountTotal
     * @param float|null $handlingFee
     * @param float|null $paymentFee
     * @param float|null $paymentFeeTax
     * @param string|null $comment
     * @param string|null $paidAt
     *
     * @return Tip
     *
     * @throws DatabaseException
     */
    public function update(
        Tip $tip,
        ?OrderItem $orderItem,
        ?PaymentMethod $paymentMethod,
        ?User $buyer,
        ?User $seller,
        ?float $amount,
        ?float $amountEarned,
        ?float $amountTax,
        ?float $amountTotal,
        ?float $handlingFee,
        ?float $paymentFee,
        ?float $paymentFeeTax,
        ?string $comment,
        ?string $paidAt
    ) : Tip
    {
        try {
            $tip->update([
                'item_id'         => $orderItem ? $orderItem->id : $tip->item_id,
                'method_id'       => $paymentMethod ? $paymentMethod->id : $tip->method_id,
                'buyer_id'        => $buyer ? $buyer->id : $tip->buyer_id,
                'seller_id'       => $seller ? $seller->id : $tip->seller_id,
                'amount'          => $amount ?: $tip->amount,
                'amount_earned'   => $amountEarned ?: $tip->amount_earned,
                'amount_tax'      => $amountTax ?: $tip->amount_tax,
                'amount_total'    => $amountTotal ?: $tip->amount_total,
                'handling_fee'    => !is_null($handlingFee) ? $handlingFee : $tip->handling_fee,
                'payment_fee'     => $paymentFee ?: $tip->payment_fee,
                'payment_fee_tax' => !is_null($paymentFeeTax) ? $paymentFeeTax : $tip->payment_fee_tax,
                'comment'         => $comment ?: $tip->comment,
                'paid_at'         => $paidAt ?
                    Carbon::parse($paidAt)->format('Y-m-d H:i:s') :
                    null
            ]);

            return $tip;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Tip $tip
     * @param float $amount
     *
     * @return Tip
     *
     * @throws DatabaseException
     */
    public function updateAmount(
        Tip $tip,
        float $amount
    ) : Tip
    {
        try {
            $tip->update([
                'amount' => $amount
            ]);

            return $tip;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Tip $tip
     *
     * @return Tip
     *
     * @throws DatabaseException
     */
    public function updatePaidAt(
        Tip $tip
    ) : Tip
    {
        try {
            $tip->update([
                'paid_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            return $tip;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Tip $tip
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Tip $tip
    ) : bool
    {
        try {
            return $tip->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/tip/tip.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
