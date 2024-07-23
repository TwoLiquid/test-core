<?php

namespace App\Repositories\User;

use App\Exceptions\DatabaseException;
use App\Lists\Account\Status\AccountStatusListItem;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Models\MongoDb\User\Request\Deactivation\UserDeactivationRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\Interfaces\UserDeactivationRequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class UserDeactivationRequestRepository
 *
 * @package App\Repositories\User
 */
class UserDeactivationRequestRepository extends BaseRepository implements UserDeactivationRequestRepositoryInterface
{
    /**
     * UserDeactivationRequestRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.userDeactivationRequest.cacheTime');
        $this->perPage = config('repositories.userDeactivationRequest.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return UserDeactivationRequest|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?UserDeactivationRequest
    {
        try {
            return UserDeactivationRequest::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return UserDeactivationRequest|null
     *
     * @throws DatabaseException
     */
    public function findPendingForUser(
        User $user
    ) : ?UserDeactivationRequest
    {
        try {
            return UserDeactivationRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return UserDeactivationRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastForUser(
        User $user
    ) : ?UserDeactivationRequest
    {
        try {
            return UserDeactivationRequest::query()
                ->where('user_id', '=', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param bool $shown
     *
     * @return UserDeactivationRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastShownForUser(
        User $user,
        bool $shown = true
    ) : ?UserDeactivationRequest
    {
        try {
            return UserDeactivationRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('shown', '=', $shown)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
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
            return Cache::remember('userDeactivationRequests.all.count', $this->cacheTime,
                function () {
                    return UserDeactivationRequest::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
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
            return UserDeactivationRequest::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
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
            return UserDeactivationRequest::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
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
     * @param int|null $sales
     * @param array|null $languagesIds
     * @param array|null $userStatusesIds
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
        ?string $requestId = null,
        ?int $userId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $username = null,
        ?int $sales = null,
        ?array $languagesIds = null,
        ?array $userStatusesIds = null,
        ?array $requestStatusesIds = null,
        ?string $admin = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return UserDeactivationRequest::query()
                ->with([
                    'user' => function ($query) {
                        $query->withCount([
                            'sales'
                        ]);
                    },
                    'admin' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'last_name',
                            'first_name'
                        ]);
                    }
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
                ->when($sales, function ($query) use ($sales) {
                    $query->whereHas('user', function($query) use ($sales) {
                        $query->withCount('sales')
                            ->having('sales_count', '=', $sales);
                    });
                })
                ->when($languagesIds, function ($query) use ($languagesIds) {
                    $query->whereIn('language_id', $languagesIds);
                })
                ->when($userStatusesIds, function ($query) use ($userStatusesIds) {
                    $query->whereHas('user', function ($query) use ($userStatusesIds) {
                        $query->whereIn('account_status_id', $userStatusesIds);
                    });
                })
                ->when($requestStatusesIds, function ($query) use ($requestStatusesIds) {
                    $query->whereIn('request_status_id', $requestStatusesIds);
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

                    if ($sortBy == 'request_status') {
                        $query->orderBy('request_status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
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
     * @param int|null $sales
     * @param array|null $languagesIds
     * @param array|null $userStatusesIds
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
        ?string $requestId = null,
        ?int $userId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $username = null,
        ?int $sales = null,
        ?array $languagesIds = null,
        ?array $userStatusesIds = null,
        ?array $requestStatusesIds = null,
        ?string $admin = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return UserDeactivationRequest::query()
                ->with([
                    'user' => function ($query) {
                        $query->withCount([
                            'sales'
                        ]);
                    }
                ])
                ->when($requestId, function ($query) use ($requestId) {
                    $query->where('id', '=', $requestId);
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
                ->when($sales, function ($query) use ($sales) {
                    $query->whereHas('user', function($query) use ($sales) {
                        $query->withCount('sales')
                            ->having('sales_count', '=', $sales);
                    });
                })
                ->when($languagesIds, function ($query) use ($languagesIds) {
                    $query->whereIn('language_id', $languagesIds);
                })
                ->when($userStatusesIds, function ($query) use ($userStatusesIds) {
                    $query->whereHas('user', function ($query) use ($userStatusesIds) {
                        $query->whereIn('account_status_id', $userStatusesIds);
                    });
                })
                ->when($requestStatusesIds, function ($query) use ($requestStatusesIds) {
                    $query->whereIn('request_status_id', $requestStatusesIds);
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

                    if ($sortBy == 'request_status') {
                        $query->orderBy('request_status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
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
            return UserDeactivationRequest::query()
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
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
            return UserDeactivationRequest::query()
                ->whereIn('_id', $ids)
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
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
    public function existsForUser(
        User $user
    ) : bool
    {
        try {
            return UserDeactivationRequest::query()
                ->where('user_id', '=', $user->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
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
            return UserDeactivationRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string|null $reason
     * @param AccountStatusListItem $accountStatusListItem
     * @param AccountStatusListItem $previousAccountStatusListItem
     *
     * @return UserDeactivationRequest|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        ?string $reason,
        AccountStatusListItem $accountStatusListItem,
        AccountStatusListItem $previousAccountStatusListItem
    ) : ?UserDeactivationRequest
    {
        try {
            return UserDeactivationRequest::query()->create([
                'user_id'                    => $user->id,
                'reason'                     => $reason,
                'account_status_id'          => $accountStatusListItem->id,
                'account_status_status_id'   => RequestFieldStatusList::getDefaultItem()->id,
                'previous_account_status_id' => $previousAccountStatusListItem->id,
                'request_status_id'          => RequestStatusList::getDefaultItem()->id,
                'toast_message_type_id'      => ToastMessageTypeList::getSubmittedItem()->id,
                'toast_message_text'         => null,
                'shown'                      => false,
                'admin_id'                   => null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     * @param RequestFieldStatusListItem|null $accountStatusStatus
     * @param string|null $toastMessageText
     *
     * @return UserDeactivationRequest|null
     *
     * @throws DatabaseException
     */
    public function update(
        UserDeactivationRequest $userDeactivationRequest,
        ?RequestFieldStatusListItem $accountStatusStatus,
        ?string $toastMessageText
    ) : ?UserDeactivationRequest
    {
        try {
            $userDeactivationRequest->update([
                'account_status_status_id' => $accountStatusStatus?->id,
                'toast_message_text'       => $toastMessageText ?: null
            ]);

            return $userDeactivationRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return UserDeactivationRequest
     *
     * @throws DatabaseException
     */
    public function updateRequestStatus(
        UserDeactivationRequest $userDeactivationRequest,
        RequestStatusListItem $requestStatusListItem
    ) : UserDeactivationRequest
    {
        try {
            $userDeactivationRequest->update([
                'request_status_id' => $requestStatusListItem->id
            ]);

            return $userDeactivationRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/UserDeactivationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     *
     * @return UserDeactivationRequest
     *
     * @throws DatabaseException
     */
    public function updateToastMessageType(
        UserDeactivationRequest $userDeactivationRequest,
        ToastMessageTypeListItem $toastMessageTypeListItem
    ) : UserDeactivationRequest
    {
        try {
            $userDeactivationRequest->update([
                'toast_message_type_id' => $toastMessageTypeListItem->id
            ]);

            return $userDeactivationRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     * @param bool $shown
     *
     * @return UserDeactivationRequest
     *
     * @throws DatabaseException
     */
    public function updateShown(
        UserDeactivationRequest $userDeactivationRequest,
        bool $shown
    ) : UserDeactivationRequest
    {
        try {
            $userDeactivationRequest->update([
                'shown' => $shown
            ]);

            return $userDeactivationRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     * @param LanguageListItem $languageListItem
     *
     * @return UserDeactivationRequest
     *
     * @throws DatabaseException
     */
    public function updateLanguage(
        UserDeactivationRequest $userDeactivationRequest,
        LanguageListItem $languageListItem
    ) : UserDeactivationRequest
    {
        try {
            $userDeactivationRequest->update([
                'language_id' => $languageListItem->id
            ]);

            return $userDeactivationRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     * @param Admin $admin
     *
     * @return UserDeactivationRequest
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        UserDeactivationRequest $userDeactivationRequest,
        Admin $admin
    ) : UserDeactivationRequest
    {
        try {
            $userDeactivationRequest->update([
                'admin_id' => $admin->id
            ]);

            return $userDeactivationRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserDeactivationRequest $userDeactivationRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        UserDeactivationRequest $userDeactivationRequest
    ) : bool
    {
        try {
            return $userDeactivationRequest->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
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
            return UserDeactivationRequest::query()
                ->where('user_id', '=', $user->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userDeactivationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
