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
use App\Models\MongoDb\User\Request\Profile\UserProfileRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\Interfaces\UserProfileRequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class UserProfileRequestRepository
 *
 * @package App\Repositories\User
 */
class UserProfileRequestRepository extends BaseRepository implements UserProfileRequestRepositoryInterface
{
    /**
     * UserProfileRequestRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.userProfileRequest.cacheTime');
        $this->perPage = config('repositories.userProfileRequest.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?UserProfileRequest
    {
        try {
            return UserProfileRequest::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function findPendingForUser(
        User $user
    ) : ?UserProfileRequest
    {
        try {
            return UserProfileRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
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
            return UserProfileRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $username
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsUsernameForPending(
        string $username
    ) : bool
    {
        try {
            return UserProfileRequest::query()
                ->where('username', '=', $username)
                ->where('request_status_id', '=', RequestStatusList::getPendingItem()->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastForUser(
        User $user
    ) : ?UserProfileRequest
    {
        try {
            return UserProfileRequest::query()
                ->where('user_id', '=', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param bool $shown
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastShownForUser(
        User $user,
        bool $shown = true
    ) : ?UserProfileRequest
    {
        try {
            return UserProfileRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('shown', '=', $shown)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastDeclinedForUser(
        User $user
    ) : ?UserProfileRequest
    {
        try {
            return UserProfileRequest::query()
                ->where('user_id', '=', $user->id)
                ->where('request_status_id', '=', RequestStatusList::getDeclinedItem()->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
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
            return Cache::remember('userProfileRequests.all.count', $this->cacheTime,
                function () {
                    return UserProfileRequest::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
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
            return UserProfileRequest::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
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
            return UserProfileRequest::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
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
            return UserProfileRequest::query()
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
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
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
            return UserProfileRequest::query()
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
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
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
            return UserProfileRequest::query()
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
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
            return UserProfileRequest::query()
                ->whereIn('_id', $ids)
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param AccountStatusListItem|null $accountStatusListItem
     * @param AccountStatusListItem|null $previousAccountStatusListItem
     * @param string|null $username
     * @param string|null $previousUsername
     * @param string|null $birthDate
     * @param string|null $previousBirthDate
     * @param string|null $description
     * @param string|null $previousDescription
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        ?AccountStatusListItem $accountStatusListItem,
        ?AccountStatusListItem $previousAccountStatusListItem,
        ?string $username,
        ?string $previousUsername,
        ?string $birthDate,
        ?string $previousBirthDate,
        ?string $description,
        ?string $previousDescription
    ) : ?UserProfileRequest
    {
        try {
            return UserProfileRequest::query()->create([
                'user_id'                    => $user->id,
                'account_status_id'          => $accountStatusListItem?->id,
                'account_status_status_id'   => $accountStatusListItem ? RequestFieldStatusList::getPendingItem()->id : null,
                'previous_account_status_id' => $previousAccountStatusListItem?->id,
                'username'                   => $username,
                'username_status_id'         => $username ? RequestFieldStatusList::getPendingItem()->id : null,
                'previous_username'          => $previousUsername,
                'birth_date'                 => $birthDate,
                'birth_date_status_id'       => $birthDate ? RequestFieldStatusList::getPendingItem()->id : null,
                'previous_birth_date'        => $previousBirthDate,
                'description'                => $description,
                'description_status_id'      => $description ? RequestFieldStatusList::getPendingItem()->id : null,
                'previous_description'       => $previousDescription,
                'voice_sample_id'            => null,
                'voice_sample_status_id'     => null,
                'previous_voice_sample_id'   => null,
                'avatar_id'                  => null,
                'avatar_status_id'           => null,
                'previous_avatar_id'         => null,
                'background_id'              => null,
                'background_status_id'       => null,
                'previous_background_id'     => null,
                'images_ids'                 => null,
                'previous_images_ids'        => null,
                'videos_ids'                 => null,
                'previous_videos_ids'        => null,
                'album_status_id'            => null,
                'toast_message_type_id'      => ToastMessageTypeList::getSubmittedItem()->id,
                'request_status_id'          => RequestStatusList::getPendingItem()->id,
                'toast_message_text'         => null,
                'admin_id'                   => null,
                'shown'                      => false
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param RequestFieldStatusListItem|null $accountStatusStatus
     * @param RequestFieldStatusListItem|null $usernameStatus
     * @param RequestFieldStatusListItem|null $birthDateStatus
     * @param RequestFieldStatusListItem|null $descriptionStatus
     * @param RequestFieldStatusListItem|null $voiceSampleStatus
     * @param RequestFieldStatusListItem|null $avatarStatus
     * @param RequestFieldStatusListItem|null $backgroundStatus
     * @param RequestFieldStatusListItem|null $albumStatus
     * @param string|null $toastMessageText
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function update(
        UserProfileRequest $userProfileRequest,
        ?RequestFieldStatusListItem $accountStatusStatus,
        ?RequestFieldStatusListItem $usernameStatus,
        ?RequestFieldStatusListItem $birthDateStatus,
        ?RequestFieldStatusListItem $descriptionStatus,
        ?RequestFieldStatusListItem $voiceSampleStatus,
        ?RequestFieldStatusListItem $avatarStatus,
        ?RequestFieldStatusListItem $backgroundStatus,
        ?RequestFieldStatusListItem $albumStatus,
        ?string $toastMessageText
    ) : ?UserProfileRequest
    {
        try {
            $userProfileRequest->update([
                'account_status_status_id' => $accountStatusStatus?->id,
                'username_status_id'       => $usernameStatus?->id,
                'birth_date_status_id'     => $birthDateStatus?->id,
                'description_status_id'    => $descriptionStatus?->id,
                'voice_sample_status_id'   => $voiceSampleStatus?->id,
                'avatar_status_id'         => $avatarStatus?->id,
                'background_status_id'     => $backgroundStatus?->id,
                'album_status_id'          => $albumStatus?->id,
                'toast_message_text'       => $toastMessageText ?: null
            ]);

            return $userProfileRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param int|null $voiceSampleId
     * @param int|null $previousVoiceSampleId
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function updateVoiceSample(
        UserProfileRequest $userProfileRequest,
        ?int $voiceSampleId,
        ?int $previousVoiceSampleId
    ) : ?UserProfileRequest
    {
        try {
            $userProfileRequest->update([
                'voice_sample_id'          => $voiceSampleId,
                'voice_sample_status_id'   => RequestFieldStatusList::getPendingItem()->id,
                'previous_voice_sample_id' => $previousVoiceSampleId
            ]);

            return $userProfileRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param int|null $avatarId
     * @param int|null $previousAvatarId
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function updateAvatar(
        UserProfileRequest $userProfileRequest,
        ?int $avatarId,
        ?int $previousAvatarId
    ) : ?UserProfileRequest
    {
        try {
            $userProfileRequest->update([
                'avatar_id'          => $avatarId,
                'avatar_status_id'   => RequestFieldStatusList::getPendingItem()->id,
                'previous_avatar_id' => $previousAvatarId
            ]);

            return $userProfileRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param int|null $backgroundId
     * @param int|null $previousBackgroundId
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function updateBackground(
        UserProfileRequest $userProfileRequest,
        ?int $backgroundId,
        ?int $previousBackgroundId
    ) : ?UserProfileRequest
    {
        try {
            $userProfileRequest->update([
                'background_id'          => $backgroundId,
                'background_status_id'   => RequestFieldStatusList::getPendingItem()->id,
                'previous_background_id' => $previousBackgroundId
            ]);

            return $userProfileRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param array|null $imagesIds
     * @param array|null $previousImagesIds
     * @param array|null $videosIds
     * @param array|null $previousVideosIds
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function updateAlbum(
        UserProfileRequest $userProfileRequest,
        ?array $imagesIds,
        ?array $previousImagesIds,
        ?array $videosIds,
        ?array $previousVideosIds
    ) : ?UserProfileRequest
    {
        try {
            $userProfileRequest->update([
                'images_ids'          => $imagesIds ?: $userProfileRequest->images_ids,
                'previous_images_ids' => $previousImagesIds ?: $userProfileRequest->previous_images_ids,
                'videos_ids'          => $videosIds ?: $userProfileRequest->videos_ids,
                'previous_videos_ids' => $previousVideosIds ?: $userProfileRequest->previous_videos_ids,
                'album_status_id'     => ($imagesIds || $videosIds) ?
                    RequestFieldStatusList::getPendingItem()->id :
                    null
            ]);

            return $userProfileRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function updateRequestStatus(
        UserProfileRequest $userProfileRequest,
        RequestStatusListItem $requestStatusListItem
    ) : ?UserProfileRequest
    {
        try {
            $userProfileRequest->update([
                'request_status_id' => $requestStatusListItem->id
            ]);

            return $userProfileRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param ToastMessageTypeListItem $toastMessageTypeListItem
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function updateToastMessageType(
        UserProfileRequest $userProfileRequest,
        ToastMessageTypeListItem $toastMessageTypeListItem
    ) : ?UserProfileRequest
    {
        try {
            $userProfileRequest->update([
                'toast_message_type_id' => $toastMessageTypeListItem->id
            ]);

            return $userProfileRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param LanguageListItem $languageListItem
     *
     * @return UserProfileRequest
     *
     * @throws DatabaseException
     */
    public function updateLanguage(
        UserProfileRequest $userProfileRequest,
        LanguageListItem $languageListItem
    ) : UserProfileRequest
    {
        try {
            $userProfileRequest->update([
                'language_id' => $languageListItem->id
            ]);

            return $userProfileRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param bool $shown
     *
     * @return UserProfileRequest
     *
     * @throws DatabaseException
     */
    public function updateShown(
        UserProfileRequest $userProfileRequest,
        bool $shown
    ) : UserProfileRequest
    {
        try {
            $userProfileRequest->update([
                'shown' => $shown
            ]);

            return $userProfileRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param Admin $admin
     *
     * @return UserProfileRequest|null
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        UserProfileRequest $userProfileRequest,
        Admin $admin
    ) : ?UserProfileRequest
    {
        try {
            $userProfileRequest->update([
                'admin_id' => $admin->id
            ]);

            return $userProfileRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        UserProfileRequest $userProfileRequest
    ) : bool
    {
        try {
            return $userProfileRequest->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userProfileRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
