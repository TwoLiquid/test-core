<?php

namespace App\Repositories\Payout;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Models\MongoDb\Payout\PayoutMethodRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Payout\Interfaces\PayoutMethodRequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class PayoutMethodRequestRepository
 *
 * @package App\Repositories\Payout
 */
class PayoutMethodRequestRepository extends BaseRepository implements PayoutMethodRequestRepositoryInterface
{
    /**
     * PayoutMethodRequestRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.payoutMethodRequest.cacheTime');
        $this->perPage = config('repositories.payoutMethodRequest.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return PayoutMethodRequest|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?PayoutMethodRequest
    {
        try {
            return PayoutMethodRequest::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param User $user
     *
     * @return PayoutMethodRequest|null
     *
     * @throws DatabaseException
     */
    public function findPendingForUser(
        PaymentMethod $paymentMethod,
        User $user
    ) : ?PayoutMethodRequest
    {
        try {
            return PayoutMethodRequest::query()
                ->with([
                    'fields',
                    'method',
                    'user'
                ])
                ->where('method_id', '=', $paymentMethod->id)
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param User $user
     *
     * @return PayoutMethodRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastForUser(
        PaymentMethod $paymentMethod,
        User $user
    ) : ?PayoutMethodRequest
    {
        try {
            return PayoutMethodRequest::query()
                ->with([
                    'fields',
                    'method',
                    'user'
                ])
                ->where('method_id', '=', $paymentMethod->id)
                ->where('user_id', '=', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return PayoutMethodRequest|null
     *
     * @throws DatabaseException
     */
    public function findLast(
        PaymentMethod $paymentMethod,
    ) : ?PayoutMethodRequest
    {
        try {
            return PayoutMethodRequest::query()
                ->with([
                    'fields',
                    'method',
                    'user'
                ])
                ->where('method_id', '=', $paymentMethod->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
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
            return Cache::remember('payoutMethodRequests.all.count', $this->cacheTime,
                function () {
                    return PayoutMethodRequest::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
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
            return Cache::remember('payoutMethodRequests.buyers.all.count', $this->cacheTime,
                function () {
                    return PayoutMethodRequest::query()
                        ->whereHas('user.balances', function($query) {
                            $query->where('type_id', '=', UserBalanceTypeList::getBuyer()->id)
                                ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                        })->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
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
            return Cache::remember('payoutMethodRequests.sellers.all.count', $this->cacheTime,
                function () {
                    return PayoutMethodRequest::query()
                        ->whereHas('user.balances', function($query) {
                            $query->where('type_id', '=', UserBalanceTypeList::getSeller()->id)
                                ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                        })->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
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
            return Cache::remember('payoutMethodRequests.affiliates.all.count', $this->cacheTime,
                function () {
                    return PayoutMethodRequest::query()
                        ->whereHas('user.balances', function($query) {
                            $query->where('type_id', '=', UserBalanceTypeList::getAffiliate()->id)
                                ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                        })->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
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
            return PayoutMethodRequest::query()
                ->with([
                    'method',
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
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
            return PayoutMethodRequest::query()
                ->with([
                    'method',
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
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
            return PayoutMethodRequest::query()
                ->with([
                    'method',
                    'user',
                    'fields'
                ])
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
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
    public function getLastDistinctForUser(
        User $user
    ) : Collection
    {
        try {
            return PayoutMethodRequest::query()
                ->with([
                    'method',
                    'user',
                    'fields'
                ])
                ->where('user_id', '=', $user->id)
                ->where('shown', '=', false)
                ->whereIn('request_status_id', [
                    RequestStatusList::getPendingItem()->id,
                    RequestStatusList::getDeclinedItem()->id
                ])
                ->orderBy('id', 'desc')
                ->get()
                ->unique('method_id');
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $id
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $languagesIds
     * @param int|null $payoutMethodId
     * @param array|null $userBalanceTypesIds
     * @param array|null $requestStatusesIds
     * @param string|null $admin
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        ?string $id = null,
        ?int $userId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?array $languagesIds = null,
        ?int $payoutMethodId = null,
        ?array $userBalanceTypesIds = null,
        ?array $requestStatusesIds = null,
        ?string $admin = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return PayoutMethodRequest::query()
                ->with([
                    'method',
                    'user',
                    'admin'
                ])
                ->when($id, function ($query) use ($id) {
                    $query->where('_id', '=', $id);
                })
                ->when($userId, function ($query) use ($userId) {
                    $query->whereHas('user', function ($query) use ($userId) {
                        $query->where('id', '=', $userId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($languagesIds, function ($query) use ($languagesIds) {
                    $query->whereIn('language_id', $languagesIds);
                })
                ->when($payoutMethodId, function ($query) use ($payoutMethodId) {
                    $query->where('method_id', '=', $payoutMethodId);
                })
                ->when($userBalanceTypesIds, function ($query) use ($userBalanceTypesIds) {
                    $query->whereHas('user.balances', function ($query) use ($userBalanceTypesIds) {
                        $query->whereIn('type_id', $userBalanceTypesIds)
                            ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                    });
                })
                ->when($requestStatusesIds, function ($query) use ($requestStatusesIds) {
                    $query->whereIn('request_status_id', $requestStatusesIds);
                })
                ->when($admin, function ($query) use ($admin) {
                    $query->whereHas('admin', function ($query) use ($admin) {
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

                    if ($sortBy == 'request_status') {
                        $query->orderBy('request_status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $id
     * @param int|null $userId
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param array|null $languagesIds
     * @param int|null $payoutMethodId
     * @param array|null $userBalanceTypesIds
     * @param array|null $requestStatusesIds
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
        ?string $id = null,
        ?int $userId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?array $languagesIds = null,
        ?int $payoutMethodId = null,
        ?array $userBalanceTypesIds = null,
        ?array $requestStatusesIds = null,
        ?string $admin = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return PayoutMethodRequest::query()
                ->with([
                    'method',
                    'user',
                    'admin'
                ])
                ->when($id, function ($query) use ($id) {
                    $query->whereIn('_id', $id);
                })
                ->when($userId, function ($query) use ($userId) {
                    $query->whereHas('user', function ($query) use ($userId) {
                        $query->where('id', '=', $userId);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($languagesIds, function ($query) use ($languagesIds) {
                    $query->whereIn('language_id', $languagesIds);
                })
                ->when($payoutMethodId, function ($query) use ($payoutMethodId) {
                    $query->where('method_id', '=', $payoutMethodId);
                })
                ->when($userBalanceTypesIds, function ($query) use ($userBalanceTypesIds) {
                    $query->whereHas('user.balances', function ($query) use ($userBalanceTypesIds) {
                        $query->whereIn('type_id', $userBalanceTypesIds)
                            ->where('status_id', '=', UserBalanceStatusList::getActive()->id);
                    });
                })
                ->when($requestStatusesIds, function ($query) use ($requestStatusesIds) {
                    $query->whereIn('request_status_id', $requestStatusesIds);
                })
                ->when($admin, function ($query) use ($admin) {
                    $query->whereHas('admin', function ($query) use ($admin) {
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

                    if ($sortBy == 'request_status') {
                        $query->orderBy('request_status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
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
            return PayoutMethodRequest::query()
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
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
            return PayoutMethodRequest::query()
                ->whereIn('_id', $ids)
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsPendingForUser(
        User $user
    ) : bool
    {
        try {
            return PayoutMethodRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param User $user
     *
     * @return PayoutMethodRequest|null
     *
     * @throws DatabaseException
     */
    public function store(
        PaymentMethod $paymentMethod,
        User $user
    ) : ?PayoutMethodRequest
    {
        try {
            return PayoutMethodRequest::query()->create([
                'method_id'                 => $paymentMethod->id,
                'user_id'                   => $user->id,
                'request_status_id'         => RequestStatusList::getPendingItem()->id,
                'toast_message_type_id'     => ToastMessageTypeList::getSubmittedItem()->id,
                'toast_message_text'        => null,
                'shown'                     => false,
                'admin_id'                  => null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param bool $shown
     *
     * @return PayoutMethodRequest
     *
     * @throws DatabaseException
     */
    public function updateShown(
        PayoutMethodRequest $payoutMethodRequest,
        bool $shown
    ) : PayoutMethodRequest
    {
        try {
            $payoutMethodRequest->update([
                'shown' => $shown
            ]);

            return $payoutMethodRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return PayoutMethodRequest
     *
     * @throws DatabaseException
     */
    public function updateRequestStatus(
        PayoutMethodRequest $payoutMethodRequest,
        RequestStatusListItem $requestStatusListItem
    ) : PayoutMethodRequest
    {
        try {
            $payoutMethodRequest->update([
                'request_status_id' => $requestStatusListItem->id
            ]);

            return $payoutMethodRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     * @param string|null $text
     *
     * @return PayoutMethodRequest
     *
     * @throws DatabaseException
     */
    public function updateToastMessageType(
        PayoutMethodRequest $payoutMethodRequest,
        ToastMessageTypeListItem $toastMessageTypeListItem,
        ?string $text = null
    ) : PayoutMethodRequest
    {
        try {
            $payoutMethodRequest->update([
                'toast_message_type_id' => $toastMessageTypeListItem->id,
                'toast_message_text'    => $text
            ]);

            return $payoutMethodRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param LanguageListItem $languageListItem
     *
     * @return PayoutMethodRequest
     *
     * @throws DatabaseException
     */
    public function updateLanguage(
        PayoutMethodRequest $payoutMethodRequest,
        LanguageListItem $languageListItem
    ) : PayoutMethodRequest
    {
        try {
            $payoutMethodRequest->update([
                'language_id' => $languageListItem->id
            ]);

            return $payoutMethodRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param Admin $admin
     *
     * @return PayoutMethodRequest
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        PayoutMethodRequest $payoutMethodRequest,
        Admin $admin
    ) : PayoutMethodRequest
    {
        try {
            $payoutMethodRequest->update([
                'admin_id' => $admin->id
            ]);

            return $payoutMethodRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForUser(
        User $user
    ) : bool
    {
        try {
            return PayoutMethodRequest::query()
                ->where('user_id', '=', $user->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        PayoutMethodRequest $payoutMethodRequest
    ) : bool
    {
        try {
            return $payoutMethodRequest->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
