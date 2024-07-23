<?php

namespace App\Repositories\Receipt;

use App\Exceptions\DatabaseException;
use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusListItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Receipt\Interfaces\WithdrawalReceiptRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class WithdrawalReceiptRepository
 *
 * @package App\Repositories\Receipt
 */
class WithdrawalReceiptRepository extends BaseRepository implements WithdrawalReceiptRepositoryInterface
{
    /**
     * WithdrawalReceiptRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.withdrawalReceipt.cacheTime');
        $this->perPage = config('repositories.withdrawalReceipt.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return WithdrawalReceipt|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?WithdrawalReceipt
    {
        try {
            return WithdrawalReceipt::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return WithdrawalReceipt|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?int $id
    ) : ?WithdrawalReceipt
    {
        try {
            return WithdrawalReceipt::query()
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
                            'amount',
                            'transaction_fee',
                            'description',
                            'created_at',
                            'method_id'
                        ])->with([
                            'method' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
                                ]);
                            }
                        ]);
                    },
                    'request' => function ($query) {
                        $query->select([
                            '_id',
                            'user_id',
                            'method_id',
                            'receipt_id',
                            'amount',
                            'status_id',
                            'toast_message_type_id',
                            'toast_message_text',
                            'admin_id',
                            'language_id',
                            'created_at'
                        ]);
                    }
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
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
            return Cache::remember('withdrawalReceipts.all.count', $this->cacheTime,
                function () {
                    return WithdrawalReceipt::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipts.' . __FUNCTION__),
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
            return WithdrawalReceipt::query()
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
                    },
                    'request' => function ($query) {
                        $query->select([
                            '_id',
                            'user_id',
                            'method_id',
                            'receipt_id',
                            'amount',
                            'status_id',
                            'toast_message_type_id',
                            'toast_message_text',
                            'admin_id',
                            'language_id',
                            'created_at'
                        ]);
                    }
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
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
            return WithdrawalReceipt::query()
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
                    },
                    'request' => function ($query) {
                        $query->select([
                            '_id',
                            'user_id',
                            'method_id',
                            'receipt_id',
                            'amount',
                            'status_id',
                            'toast_message_type_id',
                            'toast_message_text',
                            'admin_id',
                            'language_id',
                            'created_at'
                        ]);
                    }
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     * @param string|null $requestId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $client
     * @param int|null $payoutMethodId
     * @param array|null $withdrawReceiptStatusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        ?int $id = null,
        ?string $requestId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $client = null,
        ?int $payoutMethodId = null,
        ?array $withdrawReceiptStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return WithdrawalReceipt::query()
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
                    },
                    'request' => function ($query) {
                        $query->select([
                            '_id',
                            'user_id',
                            'method_id',
                            'receipt_id',
                            'amount',
                            'status_id',
                            'toast_message_type_id',
                            'toast_message_text',
                            'admin_id',
                            'language_id',
                            'created_at'
                        ]);
                    }
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($requestId, function ($query) use ($requestId) {
                    $query->whereHas('request', function ($query) use ($requestId) {
                        $query->where('_id', '=', $requestId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($client, function ($query) use ($client) {
                    $query->whereHas('user', function($query) use ($client) {
                        $query->where('username', 'LIKE', '%'. $client . '%');
                    });
                })
                ->when($payoutMethodId, function ($query) use ($payoutMethodId) {
                    $query->whereHas('method', function ($query) use ($payoutMethodId) {
                        $query->where('id', '=', $payoutMethodId);
                    });
                })
                ->when($withdrawReceiptStatusesIds, function ($query) use ($withdrawReceiptStatusesIds) {
                    $query->whereIn('status_id', $withdrawReceiptStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'receipt_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'client') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('users.id', 'withdrawal_receipts.user_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'payout_method') {
                        $query->orderBy(
                            PaymentMethod::query()->select('name')
                                ->whereColumn('payment_methods.id', 'withdrawal_receipts.method_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
                        $query->orderBy('amount_total', $sortOrder);
                    }

                    if ($sortBy == 'payout_status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     * @param string|null $requestId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $client
     * @param int|null $payoutMethodId
     * @param array|null $withdrawReceiptStatusesIds
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
        ?string $requestId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $client = null,
        ?int $payoutMethodId = null,
        ?array $withdrawReceiptStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return WithdrawalReceipt::query()
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
                    },
                    'request' => function ($query) {
                        $query->select([
                            '_id',
                            'user_id',
                            'method_id',
                            'receipt_id',
                            'amount',
                            'status_id',
                            'toast_message_type_id',
                            'toast_message_text',
                            'admin_id',
                            'language_id',
                            'created_at'
                        ]);
                    }
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($requestId, function ($query) use ($requestId) {
                    $query->whereHas('request', function ($query) use ($requestId) {
                        $query->where('_id', '=', $requestId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($client, function ($query) use ($client) {
                    $query->whereHas('user', function($query) use ($client) {
                        $query->where('username', 'LIKE', '%'. $client . '%');
                    });
                })
                ->when($payoutMethodId, function ($query) use ($payoutMethodId) {
                    $query->whereHas('method', function ($query) use ($payoutMethodId) {
                        $query->where('id', '=', $payoutMethodId);
                    });
                })
                ->when($withdrawReceiptStatusesIds, function ($query) use ($withdrawReceiptStatusesIds) {
                    $query->whereIn('status_id', $withdrawReceiptStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'receipt_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'client') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('users.id', 'withdrawal_receipts.user_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'payout_method') {
                        $query->orderBy(
                            PaymentMethod::query()->select('name')
                                ->whereColumn('payment_methods.id', 'withdrawal_receipts.method_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
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
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     * @param string|null $requestId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $client
     * @param int|null $payoutMethodId
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForAdminLabels(
        ?int $id = null,
        ?string $requestId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $client = null,
        ?int $payoutMethodId = null
    ) : Collection
    {
        try {
            return WithdrawalReceipt::query()
                ->select([
                    'id'
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($requestId, function ($query) use ($requestId) {
                    $query->whereHas('request', function ($query) use ($requestId) {
                        $query->where('_id', '=', $requestId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($client, function ($query) use ($client) {
                    $query->whereHas('user', function($query) use ($client) {
                        $query->where('username', 'LIKE', '%'. $client . '%');
                    });
                })
                ->when($payoutMethodId, function ($query) use ($payoutMethodId) {
                    $query->whereHas('method', function ($query) use ($payoutMethodId) {
                        $query->where('id', '=', $payoutMethodId);
                    });
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $id
     * @param string|null $requestId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $payoutMethodId
     * @param array|null $withdrawReceiptStatusesIds
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
        ?string $requestId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $payoutMethodId = null,
        ?array $withdrawReceiptStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return WithdrawalReceipt::query()
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
                    },
                    'request' => function ($query) {
                        $query->select([
                            '_id',
                            'user_id',
                            'method_id',
                            'receipt_id',
                            'amount',
                            'status_id',
                            'toast_message_type_id',
                            'toast_message_text',
                            'admin_id',
                            'language_id',
                            'created_at'
                        ]);
                    }
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($requestId, function ($query) use ($requestId) {
                    $query->whereHas('request', function ($query) use ($requestId) {
                        $query->where('_id', '=', $requestId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($payoutMethodId, function ($query) use ($payoutMethodId) {
                    $query->whereHas('method', function ($query) use ($payoutMethodId) {
                        $query->where('id', '=', $payoutMethodId);
                    });
                })
                ->when($withdrawReceiptStatusesIds, function ($query) use ($withdrawReceiptStatusesIds) {
                    $query->whereIn('status_id', $withdrawReceiptStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'receipt_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'payout_method') {
                        $query->orderBy(
                            PaymentMethod::query()->select('name')
                                ->whereColumn('payment_methods.id', 'withdrawal_receipts.method_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
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
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $id
     * @param string|null $requestId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $payoutMethodId
     * @param array|null $withdrawReceiptStatusesIds
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
        ?string $requestId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $payoutMethodId = null,
        ?array $withdrawReceiptStatusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return WithdrawalReceipt::query()
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
                    },
                    'request' => function ($query) {
                        $query->select([
                            '_id',
                            'user_id',
                            'method_id',
                            'receipt_id',
                            'amount',
                            'status_id',
                            'toast_message_type_id',
                            'toast_message_text',
                            'admin_id',
                            'language_id',
                            'created_at'
                        ]);
                    }
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($requestId, function ($query) use ($requestId) {
                    $query->whereHas('request', function ($query) use ($requestId) {
                        $query->where('_id', '=', $requestId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($payoutMethodId, function ($query) use ($payoutMethodId) {
                    $query->whereHas('method', function ($query) use ($payoutMethodId) {
                        $query->where('id', '=', $payoutMethodId);
                    });
                })
                ->when($withdrawReceiptStatusesIds, function ($query) use ($withdrawReceiptStatusesIds) {
                    $query->whereIn('status_id', $withdrawReceiptStatusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'receipt_id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'date') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'payout_method') {
                        $query->orderBy(
                            PaymentMethod::query()->select('name')
                                ->whereColumn('payment_methods.id', 'withdrawal_receipts.method_id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'total') {
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
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $id
     * @param string|null $requestId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param int|null $payoutMethodId
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForUserForAdminLabels(
        User $user,
        ?int $id = null,
        ?string $requestId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?int $payoutMethodId = null
    ) : Collection
    {
        try {
            return WithdrawalReceipt::query()
                ->select([
                    'id'
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '=', $id);
                })
                ->when($requestId, function ($query) use ($requestId) {
                    $query->whereHas('request', function ($query) use ($requestId) {
                        $query->where('_id', '=', $requestId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($payoutMethodId, function ($query) use ($payoutMethodId) {
                    $query->whereHas('method', function ($query) use ($payoutMethodId) {
                        $query->where('id', '=', $payoutMethodId);
                    });
                })
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $withdrawalReceiptsIds
     *
     * @return WithdrawalReceipt
     *
     * @throws DatabaseException
     */
    public function getStatusesByIdsCount(
        array $withdrawalReceiptsIds
    ) : WithdrawalReceipt
    {
        try {
            return WithdrawalReceipt::query()
                ->whereIn('id', $withdrawalReceiptsIds)
                ->selectRaw('sum(case when status_id = 1 then 1 else 0 end) as unpaid')
                ->selectRaw('sum(case when status_id = 2 then 1 else 0 end) as paid')
                ->selectRaw('sum(case when status_id = 3 then 1 else 0 end) as credit')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $receiptId
     * @param string|null $requestId
     * @param string|null $requestDateFrom
     * @param string|null $requestDateTo
     * @param string|null $receiptDateFrom
     * @param string|null $receiptDateTo
     * @param int|null $amount
     * @param array|null $payoutMethodsIds
     * @param array|null $withdrawReceiptStatusesIds
     * @param array|null $requestStatusesIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForDashboardFiltered(
        User $user,
        ?int $receiptId = null,
        ?string $requestId = null,
        ?string $requestDateFrom = null,
        ?string $requestDateTo = null,
        ?string $receiptDateFrom = null,
        ?string $receiptDateTo = null,
        ?int $amount = null,
        ?array $payoutMethodsIds = null,
        ?array $withdrawReceiptStatusesIds = null,
        ?array $requestStatusesIds = null
    ) : Collection
    {
        try {
            return WithdrawalReceipt::query()
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
                    'request' => function ($query) {
                        $query->select([
                            '_id',
                            'user_id',
                            'method_id',
                            'receipt_id',
                            'amount',
                            'status_id',
                            'toast_message_type_id',
                            'toast_message_text',
                            'admin_id',
                            'language_id',
                            'created_at'
                        ]);
                    }
                ])
                ->when($receiptId, function ($query) use ($receiptId) {
                    $query->where('id', '=', $receiptId);
                })
                ->when($requestId, function ($query) use ($requestId) {
                    $query->whereHas('request', function ($query) use ($requestId) {
                        $query->where('_id', '=', $requestId);
                    });
                })
                ->when($receiptDateFrom, function ($query) use ($receiptDateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($receiptDateFrom));
                })
                ->when($receiptDateTo, function ($query) use ($receiptDateTo) {
                    $query->where('created_at', '<=', Carbon::parse($receiptDateTo));
                })
                ->when($requestDateFrom, function ($query) use ($requestDateFrom) {
                    $query->whereHas('request', function ($query) use ($requestDateFrom) {
                        $query->where('created_at', '>=', Carbon::parse($requestDateFrom));
                    });
                })
                ->when($requestDateTo, function ($query) use ($requestDateTo) {
                    $query->whereHas('request', function ($query) use ($requestDateTo) {
                        $query->where('created_at', '<=', Carbon::parse($requestDateTo));
                    });
                })
                ->when($amount, function ($query) use ($amount) {
                    $query->where('amount', '=', $amount);
                })
                ->when($payoutMethodsIds, function ($query) use ($payoutMethodsIds) {
                    $query->whereHas('method', function ($query) use ($payoutMethodsIds) {
                        $query->whereIn('id', $payoutMethodsIds);
                    });
                })
                ->when($withdrawReceiptStatusesIds, function ($query) use ($withdrawReceiptStatusesIds) {
                    $query->whereIn('status_id', $withdrawReceiptStatusesIds);
                })
                ->when($requestStatusesIds, function ($query) use ($requestStatusesIds) {
                    $query->whereHas('request', function ($query) use ($requestStatusesIds) {
                        $query->whereIn('status_id', $requestStatusesIds);
                    });
                })
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $receiptId
     * @param string|null $requestId
     * @param string|null $requestDateFrom
     * @param string|null $requestDateTo
     * @param string|null $receiptDateFrom
     * @param string|null $receiptDateTo
     * @param int|null $amount
     * @param array|null $payoutMethodsIds
     * @param array|null $withdrawReceiptStatusesIds
     * @param array|null $requestStatusesIds
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
        ?string $requestId = null,
        ?string $requestDateFrom = null,
        ?string $requestDateTo = null,
        ?string $receiptDateFrom = null,
        ?string $receiptDateTo = null,
        ?int $amount = null,
        ?array $payoutMethodsIds = null,
        ?array $withdrawReceiptStatusesIds = null,
        ?array $requestStatusesIds = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return WithdrawalReceipt::query()
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
                    },
                    'request' => function ($query) {
                        $query->select([
                            '_id',
                            'user_id',
                            'method_id',
                            'receipt_id',
                            'amount',
                            'status_id',
                            'toast_message_type_id',
                            'toast_message_text',
                            'admin_id',
                            'language_id',
                            'created_at'
                        ]);
                    }
                ])
                ->when($receiptId, function ($query) use ($receiptId) {
                    $query->where('id', '=', $receiptId);
                })
                ->when($requestId, function ($query) use ($requestId) {
                    $query->whereHas('request', function ($query) use ($requestId) {
                        $query->where('_id', '=', $requestId);
                    });
                })
                ->when($receiptDateFrom, function ($query) use ($receiptDateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($receiptDateFrom));
                })
                ->when($receiptDateTo, function ($query) use ($receiptDateTo) {
                    $query->where('created_at', '<=', Carbon::parse($receiptDateTo));
                })
                ->when($requestDateFrom, function ($query) use ($requestDateFrom) {
                    $query->whereHas('request', function ($query) use ($requestDateFrom) {
                        $query->where('created_at', '>=', Carbon::parse($requestDateFrom));
                    });
                })
                ->when($requestDateTo, function ($query) use ($requestDateTo) {
                    $query->whereHas('request', function ($query) use ($requestDateTo) {
                        $query->where('created_at', '<=', Carbon::parse($requestDateTo));
                    });
                })
                ->when($amount, function ($query) use ($amount) {
                    $query->where('amount', '=', $amount);
                })
                ->when($payoutMethodsIds, function ($query) use ($payoutMethodsIds) {
                    $query->whereHas('method', function ($query) use ($payoutMethodsIds) {
                        $query->whereIn('id', $payoutMethodsIds);
                    });
                })
                ->when($withdrawReceiptStatusesIds, function ($query) use ($withdrawReceiptStatusesIds) {
                    $query->whereIn('status_id', $withdrawReceiptStatusesIds);
                })
                ->when($requestStatusesIds, function ($query) use ($requestStatusesIds) {
                    $query->whereHas('request', function ($query) use ($requestStatusesIds) {
                        $query->whereIn('status_id', $requestStatusesIds);
                    });
                })
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param PaymentMethod $payoutMethod
     * @param WithdrawalReceiptStatusListItem $withdrawalReceiptStatusListItem
     * @param string|null $description
     * @param float $amount
     * @param float|null $amountTotal
     * @param float|null $paymentFee
     *
     * @return WithdrawalReceipt|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        PaymentMethod $payoutMethod,
        WithdrawalReceiptStatusListItem $withdrawalReceiptStatusListItem,
        ?string $description,
        float $amount,
        ?float $amountTotal,
        ?float $paymentFee
    ) : ?WithdrawalReceipt
    {
        try {
            return WithdrawalReceipt::query()->create([
                'user_id'      => $user->id,
                'method_id'    => $payoutMethod->id,
                'status_id'    => $withdrawalReceiptStatusListItem->id,
                'description'  => $description,
                'amount'       => $amount,
                'amount_total' => $amountTotal,
                'payment_fee'  => $paymentFee
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param User|null $user
     * @param PaymentMethod|null $payoutMethod
     * @param WithdrawalReceiptStatusListItem|null $withdrawalReceiptStatusListItem
     * @param string|null $description
     * @param float|null $amount
     * @param float|null $amountTotal
     * @param float|null $paymentFee
     *
     * @return WithdrawalReceipt
     *
     * @throws DatabaseException
     */
    public function update(
        WithdrawalReceipt $withdrawalReceipt,
        ?User $user,
        ?PaymentMethod $payoutMethod,
        ?WithdrawalReceiptStatusListItem $withdrawalReceiptStatusListItem,
        ?string $description,
        ?float $amount,
        ?float $amountTotal,
        ?float $paymentFee
    ) : WithdrawalReceipt
    {
        try {
            $withdrawalReceipt->update([
                'user_id'      => $user ? $user->id : $withdrawalReceipt->user_id,
                'method_id'    => $payoutMethod ? $payoutMethod->id : $withdrawalReceipt->method_id,
                'status_id'    => $withdrawalReceiptStatusListItem ? $withdrawalReceiptStatusListItem->id : $withdrawalReceipt->status_id,
                'description'  => $description ?: $withdrawalReceipt->description,
                'amount'       => $amount ?: $withdrawalReceipt->amount,
                'amount_total' => $amountTotal ?: $withdrawalReceipt->amount_total,
                'payment_fee'  => $paymentFee ?: $withdrawalReceipt->payment_fee
            ]);

            return $withdrawalReceipt;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param float $paymentFee
     * @param float $amountTotal
     *
     * @return WithdrawalReceipt
     *
     * @throws DatabaseException
     */
    public function updateTotalAmountAndPaymentFee(
        WithdrawalReceipt $withdrawalReceipt,
        float $paymentFee,
        float $amountTotal
    ) : WithdrawalReceipt
    {
        try {
            $withdrawalReceipt->update([
                'payment_fee'  => $paymentFee,
                'amount_total' => $amountTotal
            ]);

            return $withdrawalReceipt;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param WithdrawalReceiptStatusListItem $withdrawalReceiptStatusListItem
     *
     * @return WithdrawalReceipt
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        WithdrawalReceipt $withdrawalReceipt,
        WithdrawalReceiptStatusListItem $withdrawalReceiptStatusListItem
    ) : WithdrawalReceipt
    {
        try {
            $withdrawalReceipt->update([
                'status_id' => $withdrawalReceiptStatusListItem->id
            ]);

            return $withdrawalReceipt;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        WithdrawalReceipt $withdrawalReceipt
    ) : bool
    {
        try {
            return $withdrawalReceipt->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalReceipt.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}