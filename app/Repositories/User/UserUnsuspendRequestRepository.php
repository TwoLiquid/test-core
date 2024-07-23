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
use App\Models\MongoDb\User\Request\Unsuspend\UserUnsuspendRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\Interfaces\UserUnsuspendRequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class UserUnsuspendRequestRepository
 *
 * @package App\Repositories\User
 */
class UserUnsuspendRequestRepository extends BaseRepository implements UserUnsuspendRequestRepositoryInterface
{
    /**
     * UserUnsuspendRequestRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.userUnsuspendRequest.cacheTime');
        $this->perPage = config('repositories.userUnsuspendRequest.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return UserUnsuspendRequest|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?UserUnsuspendRequest
    {
        try {
            return UserUnsuspendRequest::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return UserUnsuspendRequest|null
     *
     * @throws DatabaseException
     */
    public function findPendingForUser(
        User $user
    ) : ?UserUnsuspendRequest
    {
        try {
            return UserUnsuspendRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return UserUnsuspendRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastForUser(
        User $user
    ) : ?UserUnsuspendRequest
    {
        try {
            return UserUnsuspendRequest::query()
                ->where('user_id', '=', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param bool $shown
     *
     * @return UserUnsuspendRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastShownForUser(
        User $user,
        bool $shown = true
    ) : ?UserUnsuspendRequest
    {
        try {
            return UserUnsuspendRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('shown', '=', $shown)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
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
            return Cache::remember('userUnsuspendRequests.all.count', $this->cacheTime,
                function () {
                    return UserUnsuspendRequest::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
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
            return UserUnsuspendRequest::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
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
            return UserUnsuspendRequest::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
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
            return UserUnsuspendRequest::query()
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
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
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
            return UserUnsuspendRequest::query()
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
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
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
            return UserUnsuspendRequest::query()
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
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
            return UserUnsuspendRequest::query()
                ->whereIn('_id', $ids)
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
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
            return UserUnsuspendRequest::query()
                ->where('user_id', '=', $user->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
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
            return UserUnsuspendRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
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
     * @return UserUnsuspendRequest|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        ?string $reason,
        AccountStatusListItem $accountStatusListItem,
        AccountStatusListItem $previousAccountStatusListItem
    ) : ?UserUnsuspendRequest
    {
        try {
            return UserUnsuspendRequest::query()->create([
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
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     * @param RequestFieldStatusListItem|null $accountStatusStatus
     * @param string|null $toastMessageText
     *
     * @return UserUnsuspendRequest|null
     *
     * @throws DatabaseException
     */
    public function update(
        UserUnsuspendRequest $userUnsuspendRequest,
        ?RequestFieldStatusListItem $accountStatusStatus,
        ?string $toastMessageText
    ) : ?UserUnsuspendRequest
    {
        try {
            $userUnsuspendRequest->update([
                'account_status_status_id' => $accountStatusStatus?->id,
                'toast_message_text'       => $toastMessageText ?: null
            ]);

            return $userUnsuspendRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return UserUnsuspendRequest
     *
     * @throws DatabaseException
     */
    public function updateRequestStatus(
        UserUnsuspendRequest $userUnsuspendRequest,
        RequestStatusListItem $requestStatusListItem
    ) : UserUnsuspendRequest
    {
        try {
            $userUnsuspendRequest->update([
                'request_status_id' => $requestStatusListItem->id
            ]);

            return $userUnsuspendRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     *
     * @return UserUnsuspendRequest
     *
     * @throws DatabaseException
     */
    public function updateToastMessageType(
        UserUnsuspendRequest $userUnsuspendRequest,
        ToastMessageTypeListItem $toastMessageTypeListItem
    ) : UserUnsuspendRequest
    {
        try {
            $userUnsuspendRequest->update([
                'toast_message_type_id' => $toastMessageTypeListItem->id
            ]);

            return $userUnsuspendRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     * @param bool $shown
     *
     * @return UserUnsuspendRequest
     *
     * @throws DatabaseException
     */
    public function updateShown(
        UserUnsuspendRequest $userUnsuspendRequest,
        bool $shown
    ) : UserUnsuspendRequest
    {
        try {
            $userUnsuspendRequest->update([
                'shown' => $shown
            ]);

            return $userUnsuspendRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     * @param LanguageListItem $languageListItem
     *
     * @return UserUnsuspendRequest
     *
     * @throws DatabaseException
     */
    public function updateLanguage(
        UserUnsuspendRequest $userUnsuspendRequest,
        LanguageListItem $languageListItem
    ) : UserUnsuspendRequest
    {
        try {
            $userUnsuspendRequest->update([
                'language_id' => $languageListItem->id
            ]);

            return $userUnsuspendRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     * @param Admin $admin
     *
     * @return UserUnsuspendRequest
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        UserUnsuspendRequest $userUnsuspendRequest,
        Admin $admin
    ) : UserUnsuspendRequest
    {
        try {
            $userUnsuspendRequest->update([
                'admin_id' => $admin->id
            ]);

            return $userUnsuspendRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserUnsuspendRequest $userUnsuspendRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        UserUnsuspendRequest $userUnsuspendRequest
    ) : bool
    {
        try {
            return $userUnsuspendRequest->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
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
            return UserUnsuspendRequest::query()
                ->where('user_id', '=', $user->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userUnsuspendRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
