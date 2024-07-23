<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\Vybe\Access\VybeAccessListItem;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitListItem;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptListItem;
use App\Lists\Vybe\Period\VybePeriodListItem;
use App\Lists\Vybe\Showcase\VybeShowcaseListItem;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Lists\Vybe\Type\VybeTypeListItem;
use App\Models\MongoDb\Suggestion\DeviceSuggestion;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Category;
use App\Models\MySql\Device;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybeChangeRequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class VybeChangeRequestRepository
 *
 * @package App\Repositories\Vybe
 */
class VybeChangeRequestRepository extends BaseRepository implements VybeChangeRequestRepositoryInterface
{
    /**
     * VybeChangeRequestRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.vybeChangeRequest.cacheTime');
        $this->perPage = config('repositories.vybeChangeRequest.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?VybeChangeRequest
    {
        try {
            return VybeChangeRequest::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $id
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?string $id
    ) : ?VybeChangeRequest
    {
        try {
            return VybeChangeRequest::query()
                ->with([
                    'vybe' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'period_id',
                            'title',
                            'version',
                            'user_count'
                        ])->with([
                            'user' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'language_id',
                                    'email',
                                    'username'
                                ]);
                            }
                        ]);
                    },
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'parent_id',
                            'code',
                            'name'
                        ]);
                    },
                    'previousCategory' => function ($query) {
                        $query->select([
                            'id',
                            'parent_id',
                            'code',
                            'name'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
                            'parent_id',
                            'code',
                            'name'
                        ]);
                    },
                    'previousSubcategory' => function ($query) {
                        $query->select([
                            'id',
                            'parent_id',
                            'code',
                            'name'
                        ]);
                    },
                    'activity' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    },
                    'previousActivity' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    },
                    'appearanceCases' => function ($query) {
                        $query->with([
                            'unit' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name',
                                    'duration'
                                ]);
                            },
                            'previousUnit' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name',
                                    'duration'
                                ]);
                            },
                            'cityPlace.timezone.offsets',
                            'previousCityPlace.timezone.offsets',
                            'csauSuggestion'
                        ]);
                    },
                    'schedules'
                ])
                ->where('_id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function findPendingForVybe(
        Vybe $vybe
    ) : ?VybeChangeRequest
    {
        try {
            return VybeChangeRequest::query()
                ->where('vybe_id',  '=', $vybe->id)
                ->where('request_status_id',  '=', RequestStatusList::getPendingItem()->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function findLastForVybe(
        Vybe $vybe
    ) : ?VybeChangeRequest
    {
        try {
            return VybeChangeRequest::query()
                ->where('vybe_id',  '=', $vybe->id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
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
            return Cache::remember('vybeChangeRequests.all.count', $this->cacheTime,
                function () {
                    return VybeChangeRequest::query()
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
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
            return VybeChangeRequest::all();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
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
            return VybeChangeRequest::query()
                ->when($requestStatusListItem, function ($query) use ($requestStatusListItem) {
                    $query->where('request_status_id', '=', $requestStatusListItem->id);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
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
            return VybeChangeRequest::query()
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
                            },
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
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
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
            return VybeChangeRequest::query()
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
                    if ($sortBy == 'request_id') {
                        $query->orderBy('_id', $sortOrder);
                    }

                    if ($sortBy == 'request_date' || $sortBy == 'waiting') {
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
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
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
            return VybeChangeRequest::query()
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
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
            return VybeChangeRequest::query()
                ->whereIn('_id', $ids)
                ->where('request_status_id', '=', $requestStatusListItem->id)
                ->count();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe|null $vybe
     * @param string|null $title
     * @param string|null $previousTitle
     * @param Category|null $category
     * @param Category|null $previousCategory
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param Category|null $previousSubcategory
     * @param string|null $subcategorySuggestion
     * @param Activity|null $activity
     * @param Activity|null $previousActivity
     * @param string|null $activitySuggestion
     * @param array|null $devicesIds
     * @param array|null $previousDevicesIds
     * @param string|null $deviceSuggestion
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param VybePeriodListItem|null $previousVybePeriodListItem
     * @param int|null $userCount
     * @param int|null $previousUserCount
     * @param VybeTypeListItem|null $vybeTypeListItem
     * @param VybeTypeListItem|null $previousVybeTypeListItem
     * @param int|null $orderAdvance
     * @param int|null $previousOrderAdvance
     * @param array|null $imagesIds
     * @param array|null $previousImagesIds
     * @param array|null $videosIds
     * @param array|null $previousVideosIds
     * @param VybeAccessListItem|null $vybeAccessListItem
     * @param VybeAccessListItem|null $previousVybeAccessListItem
     * @param VybeShowcaseListItem|null $vybeShowcaseListItem
     * @param VybeShowcaseListItem|null $previousVybeShowcaseListItem
     * @param VybeOrderAcceptListItem|null $vybeOrderAcceptListItem
     * @param VybeOrderAcceptListItem|null $previousVybeOrderAcceptListItem
     * @param VybeStatusListItem|null $vybeStatusListItem
     * @param VybeStatusListItem|null $previousVybeStatusListItem
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function store(
        ?Vybe $vybe,
        ?string $title,
        ?string $previousTitle,
        ?Category $category,
        ?Category $previousCategory,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?Category $previousSubcategory,
        ?string $subcategorySuggestion,
        ?Activity $activity,
        ?Activity $previousActivity,
        ?string $activitySuggestion,
        ?array $devicesIds,
        ?array $previousDevicesIds,
        ?string $deviceSuggestion,
        ?VybePeriodListItem $vybePeriodListItem,
        ?VybePeriodListItem $previousVybePeriodListItem,
        ?int $userCount,
        ?int $previousUserCount,
        ?VybeTypeListItem $vybeTypeListItem,
        ?VybeTypeListItem $previousVybeTypeListItem,
        ?int $orderAdvance,
        ?int $previousOrderAdvance,
        ?array $imagesIds,
        ?array $previousImagesIds,
        ?array $videosIds,
        ?array $previousVideosIds,
        ?VybeAccessListItem $vybeAccessListItem,
        ?VybeAccessListItem $previousVybeAccessListItem,
        ?VybeShowcaseListItem $vybeShowcaseListItem,
        ?VybeShowcaseListItem $previousVybeShowcaseListItem,
        ?VybeOrderAcceptListItem $vybeOrderAcceptListItem,
        ?VybeOrderAcceptListItem $previousVybeOrderAcceptListItem,
        ?VybeStatusListItem $vybeStatusListItem,
        ?VybeStatusListItem $previousVybeStatusListItem
    ) : ?VybeChangeRequest
    {
        try {
            return VybeChangeRequest::query()->create([
                'vybe_id'                  => $vybe?->id,
                'title'                    => $title,
                'previous_title'           => $previousTitle,
                'title_status_id'          => $title ? RequestFieldStatusList::getPendingItem()->id : null,
                'category_id'              => $category?->id,
                'previous_category_id'     => $previousCategory?->id,
                'category_suggestion'      => $categorySuggestion,
                'category_status_id'       => ($category || $categorySuggestion) ?
                    RequestFieldStatusList::getPendingItem()->id :
                    null,
                'subcategory_id'           => $subcategory?->id,
                'previous_subcategory_id'  => $previousSubcategory?->id,
                'subcategory_suggestion'   => $subcategorySuggestion,
                'subcategory_status_id'    => ($subcategory || $subcategorySuggestion) ?
                    RequestFieldStatusList::getPendingItem()->id :
                    null,
                'activity_id'              => $activity?->id,
                'previous_activity_id'     => $previousActivity?->id,
                'activity_suggestion'      => $activitySuggestion,
                'activity_status_id'       => ($activity || $activitySuggestion) ?
                    RequestFieldStatusList::getPendingItem()->id :
                    null,
                'devices_ids'              => $devicesIds,
                'previous_devices_ids'     => $previousDevicesIds,
                'device_suggestion'        => $deviceSuggestion,
                'devices_status_id'        => ($devicesIds || $deviceSuggestion) ?
                    RequestFieldStatusList::getPendingItem()->id :
                    null,
                'period_id'                => $vybePeriodListItem?->id,
                'previous_period_id'       => $previousVybePeriodListItem?->id,
                'period_status_id'         => $vybePeriodListItem ? RequestFieldStatusList::getPendingItem()->id : null,
                'user_count'               => $userCount,
                'previous_user_count'      => $previousUserCount,
                'user_count_status_id'     => $userCount ? RequestFieldStatusList::getPendingItem()->id : null,
                'type_id'                  => $vybeTypeListItem?->id,
                'previous_type_id'         => $previousVybeTypeListItem?->id,
                'order_advance'            => $orderAdvance,
                'previous_order_advance'   => $previousOrderAdvance,
                'order_advance_status_id'  => $orderAdvance ? RequestFieldStatusList::getPendingItem()->id : null,
                'images_ids'               => $imagesIds,
                'previous_images_ids'      => $previousImagesIds,
                'videos_ids'               => $videosIds,
                'previous_videos_ids'      => $previousVideosIds,
                'access_id'                => $vybeAccessListItem?->id,
                'previous_access_id'       => $previousVybeAccessListItem?->id,
                'access_status_id'         => $vybeAccessListItem ? RequestFieldStatusList::getPendingItem()->id : null,
                'showcase_id'              => $vybeShowcaseListItem?->id,
                'previous_showcase_id'     => $previousVybeShowcaseListItem?->id,
                'showcase_status_id'       => $vybeShowcaseListItem ? RequestFieldStatusList::getPendingItem()->id : null,
                'order_accept_id'          => $vybeOrderAcceptListItem?->id,
                'previous_order_accept_id' => $previousVybeOrderAcceptListItem?->id,
                'order_accept_status_id'   => $vybeOrderAcceptListItem ? RequestFieldStatusList::getPendingItem()->id : null,
                'status_id'                => $vybeStatusListItem?->id,
                'previous_status_id'       => $previousVybeStatusListItem?->id,
                'status_status_id'         => $vybeStatusListItem ? RequestFieldStatusList::getPendingItem()->id : null,
                'request_status_id'        => RequestStatusList::getPendingItem()->id,
                'shown'                    => false
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Category $category
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function updateSuggestedCategory(
        VybeChangeRequest $vybeChangeRequest,
        Category $category
    ) : ?VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'category_id' => $category->id
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Category $subcategory
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function updateSuggestedSubcategory(
        VybeChangeRequest $vybeChangeRequest,
        Category $subcategory
    ) : ?VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'subcategory_id' => $subcategory->id
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Activity $activity
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function updateSuggestedActivity(
        VybeChangeRequest $vybeChangeRequest,
        Activity $activity
    ) : ?VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'activity_id' => $activity->id
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Device $device
     *
     * @return VybeChangeRequest
     *
     * @throws DatabaseException
     */
    public function updateSuggestedDevice(
        VybeChangeRequest $vybeChangeRequest,
        Device $device
    ) : VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'devices_ids' => array_merge(
                    $vybeChangeRequest->devices_ids ? $vybeChangeRequest->devices_ids : [], [
                        $device->id
                    ]
                )
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function updateDeviceSuggestion(
        VybeChangeRequest $vybeChangeRequest,
        DeviceSuggestion $deviceSuggestion
    ) : ?VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'device_suggestion_id' => $deviceSuggestion->_id
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param VybeAgeLimitListItem|null $vybeAgeLimitListItem
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function updateAgeLimit(
        VybeChangeRequest $vybeChangeRequest,
        ?VybeAgeLimitListItem $vybeAgeLimitListItem
    ) : ?VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'age_limit_id' => $vybeAgeLimitListItem?->id
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param RequestFieldStatusListItem|null $schedulesStatus
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function updateSchedulesStatus(
        VybeChangeRequest $vybeChangeRequest,
        ?RequestFieldStatusListItem $schedulesStatus
    ) : ?VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'schedules_status_id' => $schedulesStatus?->id
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param bool $shown
     *
     * @return VybeChangeRequest
     *
     * @throws DatabaseException
     */
    public function updateShown(
        VybeChangeRequest $vybeChangeRequest,
        bool $shown
    ) : VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'shown' => $shown
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param RequestFieldStatusListItem|null $titleStatus
     * @param RequestFieldStatusListItem|null $categoryStatus
     * @param RequestFieldStatusListItem|null $subcategoryStatus
     * @param RequestFieldStatusListItem|null $devicesStatus
     * @param RequestFieldStatusListItem|null $activityStatus
     * @param RequestFieldStatusListItem|null $periodStatus
     * @param RequestFieldStatusListItem|null $userCountStatus
     * @param RequestFieldStatusListItem|null $schedulesStatus
     * @param RequestFieldStatusListItem|null $orderAdvanceStatus
     * @param RequestFieldStatusListItem|null $accessStatus
     * @param RequestFieldStatusListItem|null $showcaseStatus
     * @param RequestFieldStatusListItem|null $orderAcceptStatus
     * @param RequestFieldStatusListItem|null $statusStatus
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function updateStatuses(
        VybeChangeRequest $vybeChangeRequest,
        ?RequestFieldStatusListItem $titleStatus,
        ?RequestFieldStatusListItem $categoryStatus,
        ?RequestFieldStatusListItem $subcategoryStatus,
        ?RequestFieldStatusListItem $devicesStatus,
        ?RequestFieldStatusListItem $activityStatus,
        ?RequestFieldStatusListItem $periodStatus,
        ?RequestFieldStatusListItem $userCountStatus,
        ?RequestFieldStatusListItem $schedulesStatus,
        ?RequestFieldStatusListItem $orderAdvanceStatus,
        ?RequestFieldStatusListItem $accessStatus,
        ?RequestFieldStatusListItem $showcaseStatus,
        ?RequestFieldStatusListItem $orderAcceptStatus,
        ?RequestFieldStatusListItem $statusStatus
    ) : ?VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'title_status_id'         => $titleStatus?->id,
                'category_status_id'      => $categoryStatus?->id,
                'subcategory_status_id'   => $subcategoryStatus?->id,
                'devices_status_id'       => $devicesStatus?->id,
                'activity_status_id'      => $activityStatus?->id,
                'period_status_id'        => $periodStatus?->id,
                'user_count_status_id'    => $userCountStatus?->id,
                'schedules_status_id'     => $schedulesStatus?->id,
                'order_advance_status_id' => $orderAdvanceStatus?->id,
                'access_status_id'        => $accessStatus?->id,
                'showcase_status_id'      => $showcaseStatus?->id,
                'order_accept_status_id'  => $orderAcceptStatus?->id,
                'status_status_id'        => $statusStatus?->id
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        VybeChangeRequest $vybeChangeRequest,
        RequestStatusListItem $requestStatusListItem
    ) : ?VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'request_status_id' => $requestStatusListItem->id
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param string|null $toastMessageText
     *
     * @return VybeChangeRequest|null
     *
     * @throws DatabaseException
     */
    public function updateToastMessageText(
        VybeChangeRequest $vybeChangeRequest,
        ?string $toastMessageText
    ) : ?VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'toast_message_text' => $toastMessageText
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param LanguageListItem $languageListItem
     *
     * @return VybeChangeRequest
     *
     * @throws DatabaseException
     */
    public function updateLanguage(
        VybeChangeRequest $vybeChangeRequest,
        LanguageListItem $languageListItem
    ) : VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'language_id' => $languageListItem->id
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Admin $admin
     *
     * @return VybeChangeRequest
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        VybeChangeRequest $vybeChangeRequest,
        Admin $admin
    ) : VybeChangeRequest
    {
        try {
            $vybeChangeRequest->update([
                'admin_id' => $admin->id
            ]);

            return $vybeChangeRequest;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VybeChangeRequest $vybeChangeRequest
    ) : bool
    {
        try {
            return $vybeChangeRequest->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequest.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
