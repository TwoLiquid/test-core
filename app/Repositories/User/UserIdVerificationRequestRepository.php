<?php

namespace App\Repositories\User;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusListItem;
use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\Interfaces\UserIdVerificationRequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class UserIdVerificationRequestRepository
 *
 * @package App\Repositories\User
 */
class UserIdVerificationRequestRepository extends BaseRepository implements UserIdVerificationRequestRepositoryInterface
{
    /**
     * UserIdVerificationRequestRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.userIdVerificationRequest.cacheTime');
        $this->perPage = config('repositories.userIdVerificationRequest.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return UserIdVerificationRequest|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?UserIdVerificationRequest
    {
        try {
            return UserIdVerificationRequest::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return UserIdVerificationRequest|null
     *
     * @throws DatabaseException
     */
    public function findPendingForUser(
        User $user
    ) : ?UserIdVerificationRequest
    {
        try {
            return UserIdVerificationRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return UserIdVerificationRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastDeclinedForUser(
        User $user
    ) : ?UserIdVerificationRequest
    {
        try {
            return UserIdVerificationRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getDeclinedItem()->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return UserIdVerificationRequest|null
     *
     * @throws DatabaseException
     */
    public function findAcceptedForUser(
        User $user
    ) : ?UserIdVerificationRequest
    {
        try {
            return UserIdVerificationRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getAcceptedItem()->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return UserIdVerificationRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastForUser(
        User $user
    ) : ?UserIdVerificationRequest
    {
        try {
            return UserIdVerificationRequest::query()
                ->where('user_id', '=', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param bool $shown
     *
     * @return UserIdVerificationRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastShownForUser(
        User $user,
        bool $shown = true
    ) : ?UserIdVerificationRequest
    {
        try {
            return UserIdVerificationRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('shown', '=', $shown)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
            return Cache::remember('userIdVerificationRequests.all.count', $this->cacheTime,
                function () {
                    return UserIdVerificationRequest::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
            return UserIdVerificationRequest::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
            return UserIdVerificationRequest::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
            return UserIdVerificationRequest::query()
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
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
            return UserIdVerificationRequest::query()
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
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
            return UserIdVerificationRequest::query()
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
            return UserIdVerificationRequest::query()
                ->whereIn('_id', $ids)
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
    public function getLastDeclinedForUser(
        User $user
    ) : Collection
    {
        try {
            return UserIdVerificationRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getDeclinedItem()->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
            return UserIdVerificationRequest::query()
                ->where('user_id', '=', $user->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
    public function existsAcceptedForUser(
        User $user
    ) : bool
    {
        try {
            return UserIdVerificationRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getAcceptedItem()->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
    public function getForUser(
        User $user
    ) : Collection
    {
        try {
            return UserIdVerificationRequest::query()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    }
                ])
                ->where('user_id', '=', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
    public function getNotAcceptedForUser(
        User $user
    ) : Collection
    {
        try {
            return UserIdVerificationRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '!=', RequestStatusList::getAcceptedItem()->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
            return UserIdVerificationRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param UserIdVerificationStatusListItem $userIdVerificationStatusListItem
     *
     * @return UserIdVerificationRequest|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        UserIdVerificationStatusListItem $userIdVerificationStatusListItem
    ) : ?UserIdVerificationRequest
    {
        try {
            return UserIdVerificationRequest::query()->create([
                'user_id'                         => $user->id,
                'verification_suspended'          => $user->verification_suspended,
                'verification_status_id'          => $userIdVerificationStatusListItem->id,
                'verification_status_status_id'   => RequestFieldStatusList::getPendingItem()->id,
                'previous_verification_status_id' => $user->getIdVerificationStatus() ? $user->getIdVerificationStatus()->id : null,
                'request_status_id'               => RequestStatusList::getPendingItem()->id,
                'shown'                           => false,
                'toast_message_type_id'           => ToastMessageTypeList::getSubmittedItem()->id,
                'toast_message_text'              => null,
                'admin_id'                        => null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param RequestFieldStatusListItem $requestFieldStatusListItem
     * @param string|null $toastMessageText
     *
     * @return UserIdVerificationRequest|null
     *
     * @throws DatabaseException
     */
    public function update(
        UserIdVerificationRequest $userIdVerificationRequest,
        RequestFieldStatusListItem $requestFieldStatusListItem,
        ?string $toastMessageText
    ) : ?UserIdVerificationRequest
    {
        try {
            $userIdVerificationRequest->update([
                'verification_status_status_id' => $requestFieldStatusListItem->id,
                'toast_message_text'            => $toastMessageText ?: null
            ]);

            return $userIdVerificationRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param bool $shown
     *
     * @return UserIdVerificationRequest
     *
     * @throws DatabaseException
     */
    public function updateShown(
        UserIdVerificationRequest $userIdVerificationRequest,
        bool $shown
    ) : UserIdVerificationRequest
    {
        try {
            $userIdVerificationRequest->update([
                'shown' => $shown
            ]);

            return $userIdVerificationRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return UserIdVerificationRequest
     *
     * @throws DatabaseException
     */
    public function updateRequestStatus(
        UserIdVerificationRequest $userIdVerificationRequest,
        RequestStatusListItem $requestStatusListItem
    ) : UserIdVerificationRequest
    {
        try {
            $userIdVerificationRequest->update([
                'request_status_id' => $requestStatusListItem->id
            ]);

            return $userIdVerificationRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     *
     * @return UserIdVerificationRequest
     *
     * @throws DatabaseException
     */
    public function updateToastMessageType(
        UserIdVerificationRequest $userIdVerificationRequest,
        ToastMessageTypeListItem $toastMessageTypeListItem
    ) : UserIdVerificationRequest
    {
        try {
            $userIdVerificationRequest->update([
                'toast_message_type_id' => $toastMessageTypeListItem->id
            ]);

            return $userIdVerificationRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param LanguageListItem $languageListItem
     *
     * @return UserIdVerificationRequest
     *
     * @throws DatabaseException
     */
    public function updateLanguage(
        UserIdVerificationRequest $userIdVerificationRequest,
        LanguageListItem $languageListItem
    ) : UserIdVerificationRequest
    {
        try {
            $userIdVerificationRequest->update([
                'language_id' => $languageListItem->id
            ]);

            return $userIdVerificationRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param Admin $admin
     *
     * @return UserIdVerificationRequest
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        UserIdVerificationRequest $userIdVerificationRequest,
        Admin $admin
    ) : UserIdVerificationRequest
    {
        try {
            $userIdVerificationRequest->update([
                'admin_id' => $admin->id
            ]);

            return $userIdVerificationRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        UserIdVerificationRequest $userIdVerificationRequest
    ) : bool
    {
        try {
            return $userIdVerificationRequest->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
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
            return UserIdVerificationRequest::query()
                ->where('user_id', '=', $user->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userIdVerificationRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
