<?php

namespace App\Repositories\Receipt;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Models\MongoDb\WithdrawalRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Receipt\Interfaces\WithdrawalRequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class WithdrawalRequestRepository
 *
 * @package App\Repositories\Receipt
 */
class WithdrawalRequestRepository extends BaseRepository implements WithdrawalRequestRepositoryInterface
{
    /**
     * WithdrawalRequestRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.withdrawalRequest.cacheTime');
        $this->perPage = config('repositories.withdrawalRequest.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return WithdrawalRequest|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?WithdrawalRequest
    {
        try {
            return WithdrawalRequest::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return WithdrawalRequest|null
     *
     * @throws DatabaseException
     */
    public function findPendingForUser(
        User $user
    ) : ?WithdrawalRequest
    {
        try {
            return WithdrawalRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('status_id', '=', RequestStatusList::getPendingItem()->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return WithdrawalRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastForUser(
        User $user
    ) : ?WithdrawalRequest
    {
        try {
            return WithdrawalRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('shown', '=', false)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
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
            return Cache::remember('withdrawalRequests.all.count', $this->cacheTime,
                function () {
                    return WithdrawalRequest::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequests.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getAllBuyersCount() : int
    {
        try {
            return Cache::remember('withdrawalRequests.buyers.all.count', $this->cacheTime,
                function () {
                    return WithdrawalRequest::query()
                        ->whereHas('user.balances', function($query) {
                            $query->where('type_id', '=', UserBalanceTypeList::getBuyer()->id)
                                ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                        })->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequests.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getAllSellersCount() : int
    {
        try {
            return Cache::remember('withdrawalRequests.sellers.all.count', $this->cacheTime,
                function () {
                    return WithdrawalRequest::query()
                        ->whereHas('user.balances', function($query) {
                            $query->where('type_id', '=', UserBalanceTypeList::getSeller()->id)
                                ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                        })->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequests.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return int
     *
     * @throws DatabaseException
     */
    public function getAllAffiliatesCount() : int
    {
        try {
            return Cache::remember('withdrawalRequests.affiliates.all.count', $this->cacheTime,
                function () {
                    return WithdrawalRequest::query()
                        ->whereHas('user.balances', function($query) {
                            $query->where('type_id', '=', UserBalanceTypeList::getAffiliate()->id)
                                ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                        })->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequests.' . __FUNCTION__),
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
            return WithdrawalRequest::query()
                ->with([
                    'user',
                    'method'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
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
            return WithdrawalRequest::query()
                ->with([
                    'user',
                    'method'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $requestId
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param array|null $languagesIds
     * @param int|null $payoutMethodId
     * @param int|null $amount
     * @param array|null $userBalanceTypesIds
     * @param array|null $requestStatusesIds
     * @param array|null $receiptStatusesIds
     * @param string|null $admin
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        ?string $requestId = null,
        ?int $userId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $username = null,
        ?array $languagesIds = null,
        ?int $payoutMethodId = null,
        ?int $amount = null,
        ?array $userBalanceTypesIds = null,
        ?array $requestStatusesIds = null,
        ?array $receiptStatusesIds = null,
        ?string $admin = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return WithdrawalRequest::query()
                ->with([
                    'receipt',
                    'user.balances',
                    'method',
                    'admin'
                ])
                ->when($requestId, function ($query) use ($requestId) {
                    $query->where('_id', '=', $requestId);
                })
                ->when($userId, function ($query) use ($userId) {
                    $query->whereHas('user', function($query) use ($userId) {
                        $query->where('id', '=', $userId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('user', function($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username . '%');
                    });
                })
                ->when($languagesIds, function ($query) use ($languagesIds) {
                    $query->whereIn('language_id', $languagesIds);
                })
                ->when($payoutMethodId, function ($query) use ($payoutMethodId) {
                    $query->where('method_id', '=', $payoutMethodId);
                })
                ->when($amount, function ($query) use ($amount) {
                    $query->where('amount', '=', $amount);
                })
                ->when($userBalanceTypesIds, function ($query) use ($userBalanceTypesIds) {
                    $query->whereHas('user.balances', function ($query) use ($userBalanceTypesIds) {
                        $query->whereIn('type_id', $userBalanceTypesIds)
                            ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                    });
                })
                ->when($requestStatusesIds, function ($query) use ($requestStatusesIds) {
                    $query->whereIn('status_id', $requestStatusesIds);
                })
                ->when($receiptStatusesIds, function ($query) use ($receiptStatusesIds) {
                    $query->whereHas('receipt', function ($query) use ($receiptStatusesIds) {
                        $query->whereIn('status_id', $receiptStatusesIds);
                    });
                })
                ->when($admin, function ($query) use ($admin) {
                    $query->whereHas('admin', function($query) use ($admin) {
                        $query->where('last_name', 'LIKE', '%'. $admin . '%')
                            ->orWhere('first_name', 'LIKE', '%'. $admin . '%');
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'id') {
                        $query->orderBy('_id', $sortOrder);
                    }

                    if ($sortBy == 'created_at' || $sortBy == 'waiting') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'amount') {
                        $query->orderBy('amount', $sortOrder);
                    }

                    if ($sortBy == 'request_status') {
                        $query->orderBy('request_status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $requestId
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param array|null $languagesIds
     * @param int|null $payoutMethodId
     * @param int|null $amount
     * @param array|null $userBalanceTypesIds
     * @param array|null $requestStatusesIds
     * @param array|null $receiptStatusesIds
     * @param string|null $admin
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
        ?string $requestId = null,
        ?int $userId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $username = null,
        ?array $languagesIds = null,
        ?int $payoutMethodId = null,
        ?int $amount = null,
        ?array $userBalanceTypesIds = null,
        ?array $requestStatusesIds = null,
        ?array $receiptStatusesIds = null,
        ?string $admin = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return WithdrawalRequest::query()
                ->with([
                    'user.balances',
                    'method',
                    'admin'
                ])
                ->when($requestId, function ($query) use ($requestId) {
                    $query->where('_id', '=', $requestId);
                })
                ->when($userId, function ($query) use ($userId) {
                    $query->whereHas('user', function($query) use ($userId) {
                        $query->where('id', '=', $userId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('user', function($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username . '%');
                    });
                })
                ->when($languagesIds, function ($query) use ($languagesIds) {
                    $query->whereIn('language_id', $languagesIds);
                })
                ->when($payoutMethodId, function ($query) use ($payoutMethodId) {
                    $query->where('method_id', '=', $payoutMethodId);
                })
                ->when($amount, function ($query) use ($amount) {
                    $query->where('amount', '=', $amount);
                })
                ->when($userBalanceTypesIds, function ($query) use ($userBalanceTypesIds) {
                    $query->whereHas('user.balances', function ($query) use ($userBalanceTypesIds) {
                        $query->whereIn('type_id', $userBalanceTypesIds)
                            ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                    });
                })
                ->when($requestStatusesIds, function ($query) use ($requestStatusesIds) {
                    $query->whereIn('status_id', $requestStatusesIds);
                })
                ->when($receiptStatusesIds, function ($query) use ($receiptStatusesIds) {
                    $query->whereHas('receipt', function ($query) use ($receiptStatusesIds) {
                        $query->whereIn('status_id', $receiptStatusesIds);
                    });
                })
                ->when($admin, function ($query) use ($admin) {
                    $query->whereHas('admin', function($query) use ($admin) {
                        $query->where('last_name', 'LIKE', '%'. $admin . '%')
                            ->orWhere('first_name', 'LIKE', '%'. $admin . '%');
                    });
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'id') {
                        $query->orderBy('_id', $sortOrder);
                    }

                    if ($sortBy == 'created_at' || $sortBy == 'waiting') {
                        $query->orderBy('created_at', $sortOrder);
                    }

                    if ($sortBy == 'amount') {
                        $query->orderBy('amount', $sortOrder);
                    }

                    if ($sortBy == 'request_status') {
                        $query->orderBy('request_status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return int
     *
     * @throws DatabaseException
     */
    public function getRequestStatusCount(
        RequestStatusListItem $requestStatusListItem
    ) : int
    {
        try {
            return WithdrawalRequest::query()
                ->where('status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ids
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return int
     *
     * @throws DatabaseException
     */
    public function getRequestStatusCountByIds(
        array $ids,
        RequestStatusListItem $requestStatusListItem
    ) : int
    {
        try {
            return WithdrawalRequest::query()
                ->whereIn('_id', $ids)
                ->where('status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
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
    public function getPendingForUser(
        User $user
    ) : Collection
    {
        try {
            return WithdrawalRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('status_id', '=', RequestStatusList::getPendingItem()->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
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
    public function getPendingAndDeclinedForUser(
        User $user
    ) : Collection
    {
        try {
            return WithdrawalRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('shown', '=', false)
                ->whereIn('status_id', [
                    RequestStatusList::getPendingItem()->id,
                    RequestStatusList::getDeclinedItem()->id
                ])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param PaymentMethod $payoutMethod
     * @param int $amount
     *
     * @return WithdrawalRequest|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        PaymentMethod $payoutMethod,
        int $amount
    ) : ?WithdrawalRequest
    {
        try {
            return WithdrawalRequest::query()->create([
                'user_id'               => $user->id,
                'method_id'             => $payoutMethod->id,
                'receipt_id'            => null,
                'amount'                => $amount,
                'status_id'             => RequestStatusList::getPendingItem()->id,
                'toast_message_type_id' => ToastMessageTypeList::getSubmittedItem()->id,
                'shown'                 => false
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     * @param User|null $user
     * @param PaymentMethod|null $payoutMethod
     * @param int|null $amount
     *
     * @return WithdrawalRequest|null
     *
     * @throws DatabaseException
     */
    public function update(
        WithdrawalRequest $withdrawalRequest,
        ?User $user,
        ?PaymentMethod $payoutMethod,
        ?int $amount
    ) : ?WithdrawalRequest
    {
        try {
            $withdrawalRequest->update([
                'user_id'   => $user ? $user->id : $withdrawalRequest->user_id,
                'method_id' => $payoutMethod ? $payoutMethod->id : $withdrawalRequest->method_id,
                'amount'    => $amount ?: $withdrawalRequest->amount,
            ]);

            return $withdrawalRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return WithdrawalRequest
     *
     * @throws DatabaseException
     */
    public function updateReceipt(
        WithdrawalRequest $withdrawalRequest,
        WithdrawalReceipt $withdrawalReceipt
    ) : WithdrawalRequest
    {
        try {
            $withdrawalRequest->update([
                'receipt_id' => $withdrawalReceipt->id
            ]);

            return $withdrawalRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     * @param bool $shown
     *
     * @return WithdrawalRequest
     *
     * @throws DatabaseException
     */
    public function updateShown(
        WithdrawalRequest $withdrawalRequest,
        bool $shown
    ) : WithdrawalRequest
    {
        try {
            $withdrawalRequest->update([
                'shown' => $shown
            ]);

            return $withdrawalRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     * @param RequestStatusListItem $requestStatusListItem
     * @param string|null $toastMessageText
     *
     * @return WithdrawalRequest|null
     *
     * @throws DatabaseException
     */
    public function updateRequestStatus(
        WithdrawalRequest $withdrawalRequest,
        RequestStatusListItem $requestStatusListItem,
        ?string $toastMessageText = null
    ) : ?WithdrawalRequest
    {
        try {
            $withdrawalRequest->update([
                'status_id'          => $requestStatusListItem->id,
                'toast_message_text' => $toastMessageText ?: null
            ]);

            return $withdrawalRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     * @param Admin $admin
     *
     * @return WithdrawalRequest
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        WithdrawalRequest $withdrawalRequest,
        Admin $admin
    ) : WithdrawalRequest
    {
        try {
            $withdrawalRequest->update([
                'admin_id' => $admin->id
            ]);

            return $withdrawalRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     * @param LanguageListItem $languageListItem
     *
     * @return WithdrawalRequest
     *
     * @throws DatabaseException
     */
    public function updateLanguage(
        WithdrawalRequest $withdrawalRequest,
        LanguageListItem $languageListItem
    ) : WithdrawalRequest
    {
        try {
            $withdrawalRequest->update([
                'language_id' => $languageListItem->id
            ]);

            return $withdrawalRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        WithdrawalRequest $withdrawalRequest
    ) : bool
    {
        try {
            return $withdrawalRequest->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/receipt/withdrawalRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
