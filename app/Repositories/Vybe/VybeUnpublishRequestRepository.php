<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Models\MongoDb\Vybe\Request\Unpublish\VybeUnpublishRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybeUnpublishRequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class VybeUnpublishRequestRepository
 *
 * @package App\Repositories\Vybe
 */
class VybeUnpublishRequestRepository extends BaseRepository implements VybeUnpublishRequestRepositoryInterface
{
    /**
     * VybeUnpublishRequestRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.vybeUnpublishRequest.cacheTime');
        $this->perPage = config('repositories.vybeUnpublishRequest.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return VybeUnpublishRequest|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?VybeUnpublishRequest
    {
        try {
            return VybeUnpublishRequest::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $id
     *
     * @return VybeUnpublishRequest|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?string $id
    ) : ?VybeUnpublishRequest
    {
        try {
            return VybeUnpublishRequest::query()
                ->with([
                    'vybe.user'
                ])
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return VybeUnpublishRequest|null
     *
     * @throws DatabaseException
     */
    public function findPendingForVybe(
        Vybe $vybe
    ) : ?VybeUnpublishRequest
    {
        try {
            return VybeUnpublishRequest::query()
                ->where('vybe_id',  '=', $vybe->id)
                ->where('request_status_id',  '=', RequestStatusList::getPendingItem()->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return VybeUnpublishRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastForVybe(
        Vybe $vybe
    ) : ?VybeUnpublishRequest
    {
        try {
            return VybeUnpublishRequest::query()
                ->where('vybe_id',  '=', $vybe->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
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
            return Cache::remember('vybeUnpublishRequests.all.count', $this->cacheTime,
                function () {
                    return VybeUnpublishRequest::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param RequestStatusListItem|null $requestStatusListItem
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllWithStatus(
        ?RequestStatusListItem $requestStatusListItem
    ) : Collection
    {
        try {
            return VybeUnpublishRequest::query()
                ->when($requestStatusListItem, function ($query) use ($requestStatusListItem) {
                    $query->where('request_status_id', '=', $requestStatusListItem->id);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $requestId
     * @param int|null $vybeVersion
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $sales
     * @param array|null $languagesIds
     * @param array|null $vybeStatusesIds
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
        ?int $vybeVersion = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $username = null,
        ?int $sales = null,
        ?array $languagesIds = null,
        ?array $vybeStatusesIds = null,
        ?array $requestStatusesIds = null,
        ?string $admin = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return VybeUnpublishRequest::query()
                ->with([
                    'vybe' => function ($query) {
                        $query->select(
                            'id',
                            'user_id',
                            'status_id',
                            'version'
                        )->with([
                            'user' => function ($query) {
                                $query->select(
                                    'id',
                                    'auth_id',
                                    'account_status_id',
                                    'username'
                                );
                            }
                        ])->withCount([
                            'orderItems'
                        ]);
                    }
                ])
                ->when($requestId, function ($query) use ($requestId) {
                    $query->where('_id', '=', $requestId);
                })
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->whereHas('vybe', function($query) use ($vybeVersion) {
                        $query->where('version', '=', $vybeVersion);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('vybe.user', function($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username . '%');
                    });
                })
                ->when($sales, function ($query) use ($sales) {
                    $query->whereHas('vybe', function($query) use ($sales) {
                        $query->withCount('orderItems')
                            ->having('order_items_count', '=', $sales);
                    });
                })
                ->when($languagesIds, function ($query) use ($languagesIds) {
                    $query->whereIn('language_id', $languagesIds);
                })
                ->when($vybeStatusesIds, function ($query) use ($vybeStatusesIds) {
                    $query->whereHas('vybe', function ($query) use ($vybeStatusesIds) {
                        $query->whereIn('status_id', $vybeStatusesIds);
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
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $requestId
     * @param int|null $vybeVersion
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $sales
     * @param array|null $languagesIds
     * @param array|null $vybeStatusesIds
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
        ?int $vybeVersion = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $username = null,
        ?int $sales = null,
        ?array $languagesIds = null,
        ?array $vybeStatusesIds = null,
        ?array $requestStatusesIds = null,
        ?string $admin = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return VybeUnpublishRequest::query()
                ->with([
                    'vybe' => function ($query) {
                        $query->select(
                            'id',
                            'user_id',
                            'status_id',
                            'version'
                        )->with([
                            'user' => function ($query) {
                                $query->select(
                                    'id',
                                    'auth_id',
                                    'account_status_id',
                                    'username'
                                );
                            }
                        ])->withCount([
                            'orderItems'
                        ]);
                    }
                ])
                ->when($requestId, function ($query) use ($requestId) {
                    $query->where('id', '=', $requestId);
                })
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->whereHas('vybe', function($query) use ($vybeVersion) {
                        $query->where('version', '=', $vybeVersion);
                    });
                })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', Carbon::parse($dateFrom));
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', Carbon::parse($dateTo));
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('vybe.user', function($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username . '%');
                    });
                })
                ->when($sales, function ($query) use ($sales) {
                    $query->whereHas('vybe', function($query) use ($sales) {
                        $query->withCount('orderItems')
                            ->having('order_items_count', '=', $sales);
                    });
                })
                ->when($languagesIds, function ($query) use ($languagesIds) {
                    $query->whereIn('language_id', $languagesIds);
                })
                ->when($vybeStatusesIds, function ($query) use ($vybeStatusesIds) {
                    $query->whereHas('vybe', function ($query) use ($vybeStatusesIds) {
                        $query->whereIn('status_id', $vybeStatusesIds);
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
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
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
            return VybeUnpublishRequest::query()
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
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
            return VybeUnpublishRequest::query()
                ->whereIn('_id', $ids)
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string|null $message
     * @param VybeStatusListItem $previousVybeStatusListItem
     *
     * @return VybeUnpublishRequest|null
     *
     * @throws DatabaseException
     */
    public function store(
        Vybe $vybe,
        ?string $message,
        VybeStatusListItem $previousVybeStatusListItem
    ) : ?VybeUnpublishRequest
    {
        try {
            return VybeUnpublishRequest::query()->create([
                'vybe_id'            => $vybe->id,
                'message'            => $message,
                'status_id'          => VybeStatusList::getDraftItem()->id,
                'previous_status_id' => $previousVybeStatusListItem->id,
                'status_status_id'   => RequestFieldStatusList::getPendingItem()->id,
                'request_status_id'  => RequestStatusList::getPendingItem()->id,
                'shown'              => false
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     * @param RequestFieldStatusListItem $statusStatus
     *
     * @return VybeUnpublishRequest|null
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        VybeUnpublishRequest $vybeUnpublishRequest,
        RequestFieldStatusListItem $statusStatus
    ) : ?VybeUnpublishRequest
    {
        try {
            $vybeUnpublishRequest->update([
                'status_status_id' => $statusStatus->id
            ]);

            return $vybeUnpublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     * @param bool $shown
     *
     * @return VybeUnpublishRequest
     *
     * @throws DatabaseException
     */
    public function updateShown(
        VybeUnpublishRequest $vybeUnpublishRequest,
        bool $shown
    ) : VybeUnpublishRequest
    {
        try {
            $vybeUnpublishRequest->update([
                'shown' => $shown
            ]);

            return $vybeUnpublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return VybeUnpublishRequest|null
     *
     * @throws DatabaseException
     */
    public function updateRequestStatus(
        VybeUnpublishRequest $vybeUnpublishRequest,
        RequestStatusListItem $requestStatusListItem
    ) : ?VybeUnpublishRequest
    {
        try {
            $vybeUnpublishRequest->update([
                'request_status_id' => $requestStatusListItem->id
            ]);

            return $vybeUnpublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     * @param string|null $toastMessageText
     *
     * @return VybeUnpublishRequest|null
     *
     * @throws DatabaseException
     */
    public function updateToastMessageText(
        VybeUnpublishRequest $vybeUnpublishRequest,
        ?string $toastMessageText
    ) : ?VybeUnpublishRequest
    {
        try {
            $vybeUnpublishRequest->update([
                'toast_message_text' => $toastMessageText
            ]);

            return $vybeUnpublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     * @param LanguageListItem $languageListItem
     *
     * @return VybeUnpublishRequest
     *
     * @throws DatabaseException
     */
    public function updateLanguage(
        VybeUnpublishRequest $vybeUnpublishRequest,
        LanguageListItem $languageListItem
    ) : VybeUnpublishRequest
    {
        try {
            $vybeUnpublishRequest->update([
                'language_id' => $languageListItem->id
            ]);

            return $vybeUnpublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     * @param Admin $admin
     *
     * @return VybeUnpublishRequest
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        VybeUnpublishRequest $vybeUnpublishRequest,
        Admin $admin
    ) : VybeUnpublishRequest
    {
        try {
            $vybeUnpublishRequest->update([
                'admin_id' => $admin->id
            ]);

            return $vybeUnpublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeUnpublishRequest $vybeUnpublishRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VybeUnpublishRequest $vybeUnpublishRequest
    ) : bool
    {
        try {
            return $vybeUnpublishRequest->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeUnpublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
