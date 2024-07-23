<?php

namespace App\Repositories\Receipt;

use App\Exceptions\DatabaseException;
use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusListItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Receipt\Interfaces\AddFundsReceiptRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class AddFundsReceiptRepository
 *
 * @package App\Repositories\Receipt
 */
class AddFundsReceiptRepository extends BaseRepository implements AddFundsReceiptRepositoryInterface
{
    /**
     * AddFundsReceiptRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.addFundsReceipt.cacheTime');
        $this->perPage = config('repositories.addFundsReceipt.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return AddFundsReceipt|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?AddFundsReceipt
    {
        try {
            return AddFundsReceipt::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return AddFundsReceipt|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?int $id
    ) : ?AddFundsReceipt
    {
        try {
            return AddFundsReceipt::query()
                ->with([
                    'user' => function ($query) {
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
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'receipt_id',
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
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
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
            return Cache::remember('addFundsReceipts.all.count', $this->cacheTime,
                function () {
                    return AddFundsReceipt::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipts.' . __FUNCTION__),
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
            return AddFundsReceipt::query()
                ->with([
                    'user' => function ($query) {
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
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'receipt_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
                        ]);
                    }
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
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
            return AddFundsReceipt::query()
                ->with([
                    'user' => function ($query) {
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
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'receipt_id',
                            'external_id',
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at'
                        ]);
                    }
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param int|null $paymentMethodId
     * @param array|null $addFundsReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFiltered(
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?int $paymentMethodId = null,
        ?array $addFundsReceiptStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return AddFundsReceipt::query()
                ->with([
                    'user' => function ($query) {
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
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'receipt_id',
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
                    $query->whereHas('user', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($paymentMethodId, function ($query) use ($paymentMethodId) {
                    $query->whereHas('method', function ($query) use ($paymentMethodId) {
                        $query->where('id', $paymentMethodId);
                    });
                })
                ->when($addFundsReceiptStatusesIds, function ($query) use ($addFundsReceiptStatusesIds) {
                    $query->whereIn('status_id', $addFundsReceiptStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'receipt_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('users.id', 'add_funds_receipts.user_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'payment_method') {
                        $query->orderBy(
                            PaymentMethod::query()->select('name')
                                ->whereColumn('payment_methods.id', 'add_funds_receipts.method_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'amount') {
                        $query->orderBy('amount', $sortOrder);
                    }

                    if ($sortBy == 'payment_fee') {
                        $query->orderBy('payment_fee', $sortOrder);
                    }

                    if ($sortBy == 'total_amount') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'payment_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param int|null $paymentMethodId
     * @param array|null $addFundsReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getPaginatedFiltered(
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?int $paymentMethodId = null,
        ?array $addFundsReceiptStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return AddFundsReceipt::query()
                ->with([
                    'user' => function ($query) {
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
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'receipt_id',
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
                    $query->whereHas('user', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($paymentMethodId, function ($query) use ($paymentMethodId) {
                    $query->whereHas('method', function ($query) use ($paymentMethodId) {
                        $query->whereIn('id', $paymentMethodId);
                    });
                })
                ->when($addFundsReceiptStatusesIds, function ($query) use ($addFundsReceiptStatusesIds) {
                    $query->whereIn('status_id', $addFundsReceiptStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'receipt_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'buyer') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('users.id', 'add_funds_receipts.user_id')
                                ->take(1),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'payment_method') {
                        $query->orderBy(
                            PaymentMethod::query()->select('name')
                                ->whereColumn('payment_methods.id', 'add_funds_receipts.method_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'amount') {
                        $query->orderBy('amount', $sortOrder);
                    }

                    if ($sortBy == 'payment_fee') {
                        $query->orderBy('payment_fee', $sortOrder);
                    }

                    if ($sortBy == 'total_amount') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'payment_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $buyer
     * @param int|null $paymentMethodId
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFilteredForAdminLabels(
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $buyer = null,
        ?int $paymentMethodId = null
    ) : Collection
    {
        try {
            return AddFundsReceipt::query()
                ->select([
                    'id'
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
                    $query->whereHas('user', function($query) use ($buyer) {
                        $query->where('username', 'LIKE', '%'. $buyer . '%');
                    });
                })
                ->when($paymentMethodId, function ($query) use ($paymentMethodId) {
                    $query->whereHas('method', function ($query) use ($paymentMethodId) {
                        $query->where('id', $paymentMethodId);
                    });
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $paymentMethodId
     * @param array|null $addFundsReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFilteredForUser(
        User $user,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $paymentMethodId = null,
        ?array $addFundsReceiptStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return AddFundsReceipt::query()
                ->with([
                    'user' => function ($query) {
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
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'receipt_id',
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
                ->when($paymentMethodId, function ($query) use ($paymentMethodId) {
                    $query->whereHas('method', function ($query) use ($paymentMethodId) {
                        $query->whereIn('id', $paymentMethodId);
                    });
                })
                ->when($addFundsReceiptStatusesIds, function ($query) use ($addFundsReceiptStatusesIds) {
                    $query->whereIn('status_id', $addFundsReceiptStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'receipt_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'payment_method') {
                        $query->orderBy(
                            PaymentMethod::query()->select('name')
                                ->whereColumn('payment_methods.id', 'add_funds_receipts.method_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'amount') {
                        $query->orderBy('amount', $sortOrder);
                    }

                    if ($sortBy == 'payment_fee') {
                        $query->orderBy('payment_fee', $sortOrder);
                    }

                    if ($sortBy == 'total_amount') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'payment_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $paymentMethodId
     * @param array|null $addFundsReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getPaginatedFilteredForUser(
        User $user,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $paymentMethodId = null,
        ?array $addFundsReceiptStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return AddFundsReceipt::query()
                ->with([
                    'user' => function ($query) {
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
                    },
                    'transactions' => function ($query) {
                        $query->select([
                            'id',
                            'receipt_id',
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
                ->when($paymentMethodId, function ($query) use ($paymentMethodId) {
                    $query->whereHas('method', function ($query) use ($paymentMethodId) {
                        $query->whereIn('id', $paymentMethodId);
                    });
                })
                ->when($addFundsReceiptStatusesIds, function ($query) use ($addFundsReceiptStatusesIds) {
                    $query->whereIn('status_id', $addFundsReceiptStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'receipt_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'payment_method') {
                        $query->orderBy(
                            PaymentMethod::query()->select('name')
                                ->whereColumn('payment_methods.id', 'add_funds_receipts.method_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'amount') {
                        $query->orderBy('amount', $sortOrder);
                    }

                    if ($sortBy == 'payment_fee') {
                        $query->orderBy('payment_fee', $sortOrder);
                    }

                    if ($sortBy == 'total_amount') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'payment_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $id
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $paymentMethodId
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFilteredForUserForAdminLabels(
        User $user,
        ?int $id = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $paymentMethodId = null
    ) : Collection
    {
        try {
            return AddFundsReceipt::query()
                ->select([
                    'id'
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
                ->when($paymentMethodId, function ($query) use ($paymentMethodId) {
                    $query->whereHas('method', function ($query) use ($paymentMethodId) {
                        $query->whereIn('id', $paymentMethodId);
                    });
                })
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $addFundsReceiptsIds
     *
     * @return AddFundsReceipt
     *
     * @throws DatabaseException
     */
    public function getStatusesByIdsCount(
        array $addFundsReceiptsIds
    ) : AddFundsReceipt
    {
        try {
            return AddFundsReceipt::query()
                ->whereIn('id', $addFundsReceiptsIds)
                ->selectRaw('sum(case when status_id = 1 then 1 else 0 end) as unpaid')
                ->selectRaw('sum(case when status_id = 2 then 1 else 0 end) as paid')
                ->selectRaw('sum(case when status_id = 3 then 1 else 0 end) as canceled')
                ->selectRaw('sum(case when status_id = 4 then 1 else 0 end) as refunded')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $receiptId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $amount
     * @param int|null $paymentFee
     * @param int|null $total
     * @param array|null $paymentMethodsIds
     * @param array|null $addFundsReceiptStatusesIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForDashboardFiltered(
        User $user,
        ?int $receiptId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $amount = null,
        ?int $paymentFee = null,
        ?int $total = null,
        ?array $paymentMethodsIds = null,
        ?array $addFundsReceiptStatusesIds = null
    ) : Collection
    {
        try {
            return AddFundsReceipt::query()
                ->with([
                    'user' => function ($query) {
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
                ])
                ->when($receiptId, function ($query) use ($receiptId) {
                    $query->where('id', '=', $receiptId);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($amount, function ($query) use ($amount) {
                    $query->where('amount', '=', $amount);
                })
                ->when($paymentFee, function ($query) use ($paymentFee) {
                    $query->where('payment_fee', '=', $paymentFee);
                })
                ->when($total, function ($query) use ($total) {
                    $query->where('amount_total', '=', $total);
                })
                ->when($paymentMethodsIds, function ($query) use ($paymentMethodsIds) {
                    $query->whereHas('method', function ($query) use ($paymentMethodsIds) {
                        $query->whereIn('id', $paymentMethodsIds);
                    });
                })
                ->when($addFundsReceiptStatusesIds, function ($query) use ($addFundsReceiptStatusesIds) {
                    $query->whereIn('status_id', $addFundsReceiptStatusesIds);
                })
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $receiptId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $amount
     * @param int|null $paymentFee
     * @param int|null $total
     * @param array|null $paymentMethodsIds
     * @param array|null $addFundsReceiptStatusesIds
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getForDashboardFilteredPaginated(
        User $user,
        ?int $receiptId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $amount = null,
        ?int $paymentFee = null,
        ?int $total = null,
        ?array $paymentMethodsIds = null,
        ?array $addFundsReceiptStatusesIds = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return AddFundsReceipt::query()
                ->with([
                    'user' => function ($query) {
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
                ])
                ->when($receiptId, function ($query) use ($receiptId) {
                    $query->where('id', '=', $receiptId);
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($amount, function ($query) use ($amount) {
                    $query->where('amount', '=', $amount);
                })
                ->when($paymentFee, function ($query) use ($paymentFee) {
                    $query->where('payment_fee', '=', $paymentFee);
                })
                ->when($total, function ($query) use ($total) {
                    $query->where('amount_total', '=', $total);
                })
                ->when($paymentMethodsIds, function ($query) use ($paymentMethodsIds) {
                    $query->whereHas('method', function ($query) use ($paymentMethodsIds) {
                        $query->whereIn('id', $paymentMethodsIds);
                    });
                })
                ->when($addFundsReceiptStatusesIds, function ($query) use ($addFundsReceiptStatusesIds) {
                    $query->whereIn('status_id', $addFundsReceiptStatusesIds);
                })
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param PaymentMethod $paymentMethod
     * @param AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem
     * @param string|null $description
     * @param float $amount
     * @param float|null $amountTotal
     * @param float|null $paymentFee
     *
     * @return AddFundsReceipt|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        PaymentMethod $paymentMethod,
        AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem,
        ?string $description,
        float $amount,
        ?float $amountTotal,
        ?float $paymentFee
    ) : ?AddFundsReceipt
    {
        try {
            return AddFundsReceipt::query()->create([
                'user_id'      => $user->id,
                'method_id'    => $paymentMethod->id,
                'status_id'    => $addFundsReceiptStatusListItem->id,
                'description'  => $description,
                'amount'       => $amount,
                'amount_total' => $amountTotal,
                'payment_fee'  => $paymentFee,
                'hash'         => generatePaymentHash()
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     * @param User|null $user
     * @param PaymentMethod|null $paymentMethod
     * @param AddFundsReceiptStatusListItem|null $addFundsReceiptStatusListItem
     * @param string|null $description
     * @param float|null $amount
     * @param float|null $amountTotal
     * @param float|null $paymentFee
     *
     * @return AddFundsReceipt
     *
     * @throws DatabaseException
     */
    public function update(
        AddFundsReceipt $addFundsReceipt,
        ?User $user,
        ?PaymentMethod $paymentMethod,
        ?AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem,
        ?string $description,
        ?float $amount,
        ?float $amountTotal,
        ?float $paymentFee
    ) : AddFundsReceipt
    {
        try {
            $addFundsReceipt->update([
                'user_id'      => $user ? $user->id : $addFundsReceipt->user_id,
                'method_id'    => $paymentMethod ? $paymentMethod->id : $addFundsReceipt->method_id,
                'status_id'    => $addFundsReceiptStatusListItem ? $addFundsReceiptStatusListItem->id : $addFundsReceipt->status_id,
                'description'  => $description ?: $addFundsReceipt->description,
                'amount'       => $amount ?: $addFundsReceipt->amount,
                'amount_total' => $amountTotal ?: $addFundsReceipt->amount_total,
                'payment_fee'  => $paymentFee ?: $addFundsReceipt->payment_fee
            ]);

            return $addFundsReceipt;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     * @param float $paymentFee
     * @param float $amountTotal
     * @param string|null $description
     * @param AddFundsReceiptStatusListItem|null $addFundsReceiptStatusListItem
     *
     * @return AddFundsReceipt
     *
     * @throws DatabaseException
     */
    public function updatePaymentFields(
        AddFundsReceipt $addFundsReceipt,
        float $paymentFee,
        float $amountTotal,
        ?string $description,
        ?AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem
    ) : AddFundsReceipt
    {
        try {
            $addFundsReceipt->update([
                'description'  => $description ?: $addFundsReceipt->description,
                'payment_fee'  => $paymentFee ?: $addFundsReceipt->payment_fee,
                'amount_total' => $amountTotal ?: $addFundsReceipt->amount_total,
                'status_id'    => $addFundsReceiptStatusListItem ?
                    $addFundsReceiptStatusListItem->id :
                    $addFundsReceipt->status_id
            ]);

            return $addFundsReceipt;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     * @param AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem
     *
     * @return AddFundsReceipt
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        AddFundsReceipt $addFundsReceipt,
        AddFundsReceiptStatusListItem $addFundsReceiptStatusListItem
    ) : AddFundsReceipt
    {
        try {
            $addFundsReceipt->update([
                'status_id' => $addFundsReceiptStatusListItem->id,
            ]);

            return $addFundsReceipt;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        AddFundsReceipt $addFundsReceipt
    ) : bool
    {
        try {
            return $addFundsReceipt->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/addFundsReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
