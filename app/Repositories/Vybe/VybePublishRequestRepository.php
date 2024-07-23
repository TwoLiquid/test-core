<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\Vybe\Access\VybeAccessListItem;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitList;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitListItem;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptListItem;
use App\Lists\Vybe\Period\VybePeriodListItem;
use App\Lists\Vybe\Showcase\VybeShowcaseListItem;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Lists\Vybe\Type\VybeTypeListItem;
use App\Models\MongoDb\Suggestion\DeviceSuggestion;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Category;
use App\Models\MySql\Device;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybePublishRequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class VybePublishRequestRepository
 *
 * @package App\Repositories\Vybe
 */
class VybePublishRequestRepository extends BaseRepository implements VybePublishRequestRepositoryInterface
{
    /**
     * VybePublishRequestRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.vybePublishRequest.cacheTime');
        $this->perPage = config('repositories.vybePublishRequest.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?VybePublishRequest
    {
        try {
            return VybePublishRequest::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $id
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?string $id
    ) : ?VybePublishRequest
    {
        try {
            return VybePublishRequest::query()
                ->with([
                    'vybe' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'type_id',
                            'title'
                        ])->with([
                            'user' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'language_id',
                                    'username',
                                    'email'
                                ])->with([
                                    'subscriptions',
                                    'subscribers'
                                ]);
                            }
                        ]);
                    },
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'parent_id',
                            'code',
                            'name',
                            'visible',
                            'position'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
                            'parent_id',
                            'code',
                            'name',
                            'visible',
                            'position'
                        ]);
                    },
                    'activity' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name',
                            'visible',
                            'position'
                        ]);
                    },
                    'appearanceCases' => function ($query) {
                        $query->with([
                            'unit' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name',
                                    'duration',
                                    'visible'
                                ]);
                            },
                            'cityPlace.timezone.offsets',
                            'csauSuggestion'
                        ]);
                    },
                    'schedules'
                ])
                ->where('_id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function findPendingForVybe(
        Vybe $vybe
    ) : ?VybePublishRequest
    {
        try {
            return VybePublishRequest::query()
                ->where('vybe_id',  '=', $vybe->id)
                ->where('request_status_id',  '=', RequestStatusList::getPendingItem()->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastForVybe(
        Vybe $vybe
    ) : ?VybePublishRequest
    {
        try {
            return VybePublishRequest::query()
                ->where('vybe_id',  '=', $vybe->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
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
            return Cache::remember('vybePublishRequests.all.count', $this->cacheTime,
                function () {
                    return VybePublishRequest::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
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
            return VybePublishRequest::all();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param RequestStatusListItem|null $requestStatusListItem
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllWithStatus(
        ?RequestStatusListItem $requestStatusListItem
    ) : Collection
    {
        try {
            return VybePublishRequest::query()
                ->when($requestStatusListItem, function ($query) use ($requestStatusListItem) {
                    $query->where('request_status_id', '=', $requestStatusListItem->id);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
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
            return VybePublishRequest::query()
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
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
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
            return VybePublishRequest::query()
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
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
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
            return VybePublishRequest::query()
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
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
            return VybePublishRequest::query()
                ->whereIn('_id', $ids)
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string $title
     * @param Category|null $category
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param string|null $subcategorySuggestion
     * @param Activity|null $activity
     * @param string|null $activitySuggestion
     * @param array|null $devicesIds
     * @param string|null $deviceSuggestion
     * @param VybePeriodListItem $vybePeriodListItem
     * @param int $userCount
     * @param VybeTypeListItem $vybeTypeListItem
     * @param int|null $orderAdvance
     * @param array|null $imagesIds
     * @param array|null $videosIds
     * @param string|null $accessPassword
     * @param VybeAccessListItem $vybeAccessListItem
     * @param VybeShowcaseListItem $vybeShowcaseListItem
     * @param VybeOrderAcceptListItem|null $vybeOrderAcceptListItem
     * @param VybeStatusListItem $vybeStatusListItem
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function store(
        Vybe $vybe,
        string $title,
        ?Category $category,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?string $subcategorySuggestion,
        ?Activity $activity,
        ?string $activitySuggestion,
        ?array $devicesIds,
        ?string $deviceSuggestion,
        VybePeriodListItem $vybePeriodListItem,
        int $userCount,
        VybeTypeListItem $vybeTypeListItem,
        ?int $orderAdvance,
        ?array $imagesIds,
        ?array $videosIds,
        ?string $accessPassword,
        VybeAccessListItem $vybeAccessListItem,
        VybeShowcaseListItem $vybeShowcaseListItem,
        ?VybeOrderAcceptListItem $vybeOrderAcceptListItem,
        VybeStatusListItem $vybeStatusListItem
    ) : ?VybePublishRequest
    {
        try {
            return VybePublishRequest::query()->create([
                'vybe_id'                  => $vybe->id,
                'title'                    => $title,
                'previous_title'           => null,
                'title_status_id'          => RequestFieldStatusList::getPendingItem()->id,
                'category_id'              => $category?->id,
                'previous_category_id'     => null,
                'category_suggestion'      => $categorySuggestion,
                'category_status_id'       => RequestFieldStatusList::getPendingItem()->id,
                'subcategory_id'           => $subcategory?->id,
                'previous_subcategory_id'  => null,
                'subcategory_suggestion'   => $subcategorySuggestion,
                'subcategory_status_id'    => $subcategory || $subcategorySuggestion ? RequestFieldStatusList::getPendingItem()->id : null,
                'activity_id'              => $activity?->id,
                'previous_activity_id'     => null,
                'activity_suggestion'      => $activitySuggestion,
                'activity_status_id'       => RequestFieldStatusList::getPendingItem()->id,
                'devices_ids'              => $devicesIds,
                'previous_devices_ids'     => null,
                'device_suggestion'        => $deviceSuggestion,
                'devices_status_id'        => RequestFieldStatusList::getPendingItem()->id,
                'period_id'                => $vybePeriodListItem->id,
                'previous_period_id'       => null,
                'period_status_id'         => RequestFieldStatusList::getPendingItem()->id,
                'user_count'               => $userCount,
                'previous_user_count'      => null,
                'user_count_status_id'     => RequestFieldStatusList::getPendingItem()->id,
                'type_id'                  => $vybeTypeListItem->id,
                'previous_type_id'         => null,
                'schedules_status_id'      => RequestFieldStatusList::getPendingItem()->id,
                'order_advance'            => $orderAdvance,
                'previous_order_advance'   => null,
                'order_advance_status_id'  => $orderAdvance ? RequestFieldStatusList::getPendingItem()->id : null,
                'images_ids'               => $imagesIds,
                'previous_images_ids'      => null,
                'videos_ids'               => $videosIds,
                'previous_videos_ids'      => null,
                'access_password'          => $accessPassword,
                'access_id'                => $vybeAccessListItem->id,
                'previous_access_id'       => null,
                'access_status_id'         => RequestFieldStatusList::getPendingItem()->id,
                'showcase_id'              => $vybeShowcaseListItem->id,
                'previous_showcase_id'     => null,
                'showcase_status_id'       => RequestFieldStatusList::getPendingItem()->id,
                'order_accept_id'          => $vybeOrderAcceptListItem?->id,
                'previous_order_accept_id' => null,
                'order_accept_status_id'   => RequestFieldStatusList::getPendingItem()->id,
                'age_limit_id'             => VybeAgeLimitList::getSixteenPlus()->id,
                'status_id'                => $vybeStatusListItem->id,
                'previous_status_id'       => VybeStatusList::getDraftItem()->id,
                'status_status_id'         => RequestFieldStatusList::getPendingItem()->id,
                'request_status_id'        => RequestStatusList::getPendingItem()->id,
                'shown'                    => false
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param Category $category
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function updateSuggestedCategory(
        VybePublishRequest $vybePublishRequest,
        Category $category
    ) : ?VybePublishRequest
    {
        try {
            $vybePublishRequest->update([
                'category_id' => $category->id
            ]);

            return $vybePublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param Category $subcategory
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function updateSuggestedSubcategory(
        VybePublishRequest $vybePublishRequest,
        Category $subcategory
    ) : ?VybePublishRequest
    {
        try {
            $vybePublishRequest->update([
                'subcategory_id' => $subcategory->id
            ]);

            return $vybePublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param Activity $activity
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function updateSuggestedActivity(
        VybePublishRequest $vybePublishRequest,
        Activity $activity
    ) : ?VybePublishRequest
    {
        try {
            $vybePublishRequest->update([
                'activity_id' => $activity->id
            ]);

            return $vybePublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param Device $device
     *
     * @return VybePublishRequest
     *
     * @throws DatabaseException
     */
    public function updateSuggestedDevice(
        VybePublishRequest $vybePublishRequest,
        Device $device
    ) : VybePublishRequest
    {
        try {
            $vybePublishRequest->update([
                'devices_ids' => array_merge(
                    $vybePublishRequest->devices_ids ? $vybePublishRequest->devices_ids : [], [
                        $device->id
                    ]
                )
            ]);

            return $vybePublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function updateDeviceSuggestion(
        VybePublishRequest $vybePublishRequest,
        DeviceSuggestion $deviceSuggestion
    ) : ?VybePublishRequest
    {
        try {
            $vybePublishRequest->update([
                'device_suggestion_id' => $deviceSuggestion->_id
            ]);

            return $vybePublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param VybeAgeLimitListItem|null $vybeAgeLimitListItem
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function updateAgeLimit(
        VybePublishRequest $vybePublishRequest,
        ?VybeAgeLimitListItem $vybeAgeLimitListItem
    ) : ?VybePublishRequest
    {
        try {
            $vybePublishRequest->update([
                'age_limit_id' => $vybeAgeLimitListItem?->id
            ]);

            return $vybePublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param bool $shown
     *
     * @return VybePublishRequest
     *
     * @throws DatabaseException
     */
    public function updateShown(
        VybePublishRequest $vybePublishRequest,
        bool $shown
    ) : VybePublishRequest
    {
        try {
            $vybePublishRequest->update([
                'shown' => $shown
            ]);

            return $vybePublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param RequestFieldStatusListItem $titleStatus
     * @param RequestFieldStatusListItem $categoryStatus
     * @param RequestFieldStatusListItem|null $subcategoryStatus
     * @param RequestFieldStatusListItem $devicesStatus
     * @param RequestFieldStatusListItem $activityStatus
     * @param RequestFieldStatusListItem $periodStatus
     * @param RequestFieldStatusListItem $userCountStatus
     * @param RequestFieldStatusListItem $schedulesStatus
     * @param RequestFieldStatusListItem|null $orderAdvanceStatus
     * @param RequestFieldStatusListItem $accessStatus
     * @param RequestFieldStatusListItem $showcaseStatus
     * @param RequestFieldStatusListItem $orderAcceptStatus
     * @param RequestFieldStatusListItem $statusStatus
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function updateStatuses(
        VybePublishRequest $vybePublishRequest,
        RequestFieldStatusListItem $titleStatus,
        RequestFieldStatusListItem $categoryStatus,
        ?RequestFieldStatusListItem $subcategoryStatus,
        RequestFieldStatusListItem $devicesStatus,
        RequestFieldStatusListItem $activityStatus,
        RequestFieldStatusListItem $periodStatus,
        RequestFieldStatusListItem $userCountStatus,
        RequestFieldStatusListItem $schedulesStatus,
        ?RequestFieldStatusListItem $orderAdvanceStatus,
        RequestFieldStatusListItem $accessStatus,
        RequestFieldStatusListItem $showcaseStatus,
        RequestFieldStatusListItem $orderAcceptStatus,
        RequestFieldStatusListItem $statusStatus
    ) : ?VybePublishRequest
    {
        try {
            $vybePublishRequest->update([
                'title_status_id'         => $titleStatus->id,
                'category_status_id'      => $categoryStatus->id,
                'subcategory_status_id'   => $subcategoryStatus?->id,
                'devices_status_id'       => $devicesStatus->id,
                'activity_status_id'      => $activityStatus->id,
                'period_status_id'        => $periodStatus->id,
                'user_count_status_id'    => $userCountStatus->id,
                'schedules_status_id'     => $schedulesStatus->id,
                'order_advance_status_id' => $orderAdvanceStatus?->id,
                'access_status_id'        => $accessStatus->id,
                'showcase_status_id'      => $showcaseStatus->id,
                'order_accept_status_id'  => $orderAcceptStatus->id,
                'status_status_id'        => $statusStatus->id
            ]);

            return $vybePublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function updateRequestStatus(
        VybePublishRequest $vybePublishRequest,
        RequestStatusListItem $requestStatusListItem
    ) : ?VybePublishRequest
    {
        try {
            $vybePublishRequest->update([
                'request_status_id' => $requestStatusListItem->id
            ]);

            return $vybePublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param string|null $toastMessageText
     *
     * @return VybePublishRequest|null
     *
     * @throws DatabaseException
     */
    public function updateToastMessageText(
        VybePublishRequest $vybePublishRequest,
        ?string $toastMessageText
    ) : ?VybePublishRequest
    {
        try {
            $vybePublishRequest->update([
                'toast_message_text' => $toastMessageText
            ]);

            return $vybePublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param LanguageListItem $languageListItem
     *
     * @return VybePublishRequest
     *
     * @throws DatabaseException
     */
    public function updateLanguage(
        VybePublishRequest $vybePublishRequest,
        LanguageListItem $languageListItem
    ) : VybePublishRequest
    {
        try {
            $vybePublishRequest->update([
                'language_id' => $languageListItem->id
            ]);

            return $vybePublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param Admin $admin
     *
     * @return VybePublishRequest
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        VybePublishRequest $vybePublishRequest,
        Admin $admin
    ) : VybePublishRequest
    {
        try {
            $vybePublishRequest->update([
                'admin_id' => $admin->id
            ]);

            return $vybePublishRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VybePublishRequest $vybePublishRequest
    ) : bool
    {
        try {
            return $vybePublishRequest->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
