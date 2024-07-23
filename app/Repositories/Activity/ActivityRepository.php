<?php

namespace App\Repositories\Activity;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Activity\ActivityTag;
use App\Models\MySql\Category;
use App\Models\MySql\Device;
use App\Models\MySql\Platform;
use App\Models\MySql\Unit;
use App\Repositories\Activity\Interfaces\ActivityRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class ActivityRepository
 *
 * @package App\Repositories\Activity
 */
class ActivityRepository extends BaseRepository implements ActivityRepositoryInterface
{
    /**
     * ActivityRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.activity.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Activity|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Activity
    {
        try {
            return Activity::query()
                ->with([
                    'category'
                ])
                ->withCount(
                    'vybes'
                )
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     * @param string $name
     *
     * @return Activity|null
     *
     * @throws DatabaseException
     */
    public function findByName(
        Category $category,
        string $name
    ) : ?Activity
    {
        try {
            return Activity::query()
                ->where('category_id', '=', $category->id)
                ->where('name->en', '=', $name)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $code
     *
     * @return Activity|null
     *
     * @throws DatabaseException
     */
    public function findByCode(
        ?string $code
    ) : ?Activity
    {
        try {
            return Activity::query()
                ->with([
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name',
                            'visible',
                            'position'
                        ]);
                    },
                    'units' => function ($query) {
                        $query->select([
                            'id',
                            'activity_id',
                            'code',
                            'name',
                            'duration'
                        ])
                        ->where('activity_unit.visible', '=', true)
                        ->where('units.visible', '=', true);
                    }
                ])
                ->withCount(
                    'vybes'
                )
                ->where('code', '=', trim($code))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return Activity|null
     *
     * @throws DatabaseException
     */
    public function findWithUnits(
        ?int $id
    ) : ?Activity
    {
        try {
            return Activity::query()
                ->with([
                    'units' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'activity_unit.position'
                        ]);
                    }
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
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
            return Activity::query()
                ->with([
                    'category'
                ])
                ->withCount(
                    'vybes'
                )
                ->where('visible', '=', true)
                ->orderBy('position')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getPopularActivities() : Collection
    {
        try {
            return Activity::query()
                ->withCount(
                    'vybes'
                )
                ->has('category')
                ->where('visible', '=', true)
                ->orderBy('position')
                ->limit(12)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllBySearch(
        string $search
    ) : Collection
    {
        try {
            return Activity::query()
                ->with([
                    'category'
                ])
                ->withCount(
                    'vybes'
                )
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->where('visible', '=', true)
                ->orderBy('position')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByCategory(
        Category $category
    ) : Collection
    {
        try {
            return $category->activities()
                ->with([
                    'category'
                ])
                ->withCount([
                    'vybes'
                ])
                ->where('visible', '=', true)
                ->orderBy('position')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     *
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getByCategoryPaginated(
        Category $category,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Activity::query()
                ->withCount([
                    'vybes'
                ])
                ->where('visible', '=', true)
                ->where('category_id', '=', $category->id)
                ->orWhereIn('category_id', $category->subcategories
                    ->pluck('id')
                )
                ->orderBy('position')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllByCategory(
        Category $category
    ) : Collection
    {
        try {
            return $category->activities()
                ->with([
                    'category'
                ])
                ->withCount([
                    'vybes'
                ])
                ->where('visible', '=', true)
                ->orderBy('position')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllByCategoryPaginated(
        Category $category,
        ?int $page = 1,
        ?int $perPage = 18
    ) : LengthAwarePaginator
    {
        try {
            return Activity::query()
                ->with([
                    'category'
                ])
                ->withCount([
                    'vybes'
                ])
                ->where('category_id', '=', $category->id)
                ->where('visible', '=', true)
                ->orderBy('position')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     * @param string $search
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllByCategoryWithSearch(
        Category $category,
        string $search
    ) : Collection
    {
        try {
            return Activity::query()
                ->with([
                    'category'
                ])
                ->withCount([
                    'vybes'
                ])
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->where('category_id', '=', $category->id)
                ->where('visible', '=', true)
                ->orderBy('position')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     * @param string $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllByCategoryWithSearchPaginated(
        Category $category,
        string $search,
        ?int $page = 1,
        ?int $perPage = 18
    ) : LengthAwarePaginator
    {
        try {
            return Activity::query()
                ->with([
                    'category'
                ])
                ->withCount([
                    'vybes'
                ])
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->where('category_id', '=', $category->id)
                ->where('visible', '=', true)
                ->orderBy('position')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array|null $categoriesIds
     * @param string|null $name
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByCategoriesIds(
        ?array $categoriesIds,
        ?string $name = null
    ) : Collection
    {
        try {
            return Activity::query()
                ->when($categoriesIds, function ($query) use ($categoriesIds, $name) {
                    $query->whereHas('category', function ($query) use ($categoriesIds) {
                        $query->whereIn('id', $categoriesIds);
                    })->when($name, function ($query) use ($name) {
                        $query->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($name)) . '%']);
                    });
                })
                ->when($name, function ($query) use ($name) {
                    $query->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($name)) . '%']);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     * @param string|null $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getByDevicePaginated(
        Device $device,
        ?string $search = null,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return Activity::query()
                ->whereHas('devices', function ($query) use ($device) {
                    $query->where('device_id', $device->id);
                })
                ->when($search, function ($query) use ($search) {
                    $query->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%']);
                })
                ->orderBy('position')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     * @param string|null $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getByPlatformPaginated(
        Platform $platform,
        ?string $search = null,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return Activity::query()
                ->whereHas('platforms', function ($query) use ($platform) {
                    $query->where('platform_id', $platform->id);
                })
                ->when($search, function ($query) use ($search) {
                    $query->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%']);
                })
                ->orderBy('position')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     *
     * @return Activity|null
     *
     * @throws DatabaseException
     */
    public function findRelatedActivity(
        Activity $activity
    ) : ?Activity
    {
        try {
            return Activity::query()
                ->with([
                    'vybes' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'type_id',
                            'activity_id',
                            'type_id',
                            'access_id',
                            'version',
                            'title',
                            'rating',
                            'images_ids',
                            'videos_ids'
                        ])->with([
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'category_id',
                                    'name',
                                    'code'
                                ])->with([
                                    'category' => function ($query) {
                                        $query->select([
                                            'id',
                                            'name'
                                        ]);
                                    }
                                ]);
                            },
                            'schedules' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'datetime_from',
                                    'datetime_to'
                                ]);
                            },
                            'appearanceCases' => function ($query) {
                                $query->select([
                                    'id',
                                    'vybe_id',
                                    'appearance_id',
                                    'unit_id',
                                    'price',
                                    'description'
                                ])->with([
                                    'unit' => function ($query) {
                                        $query->select([
                                            'id',
                                            'name'
                                        ]);
                                    },
                                    'platforms' => function ($query) {
                                        $query->select([
                                            'id',
                                            'name'
                                        ]);
                                    }
                                ]);
                            },
                            'devices' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
                                ]);
                            },
                            'user' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ])->limit(8);
                    }
                ])
                ->where('category_id', '=', $activity->category_id)
                ->where('id', '!=', $activity->id)
                ->has('vybes')
                ->inRandomOrder()
                ->limit(1)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
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
            return Activity::query()
                ->with([
                    'category'
                ])
                ->withCount([
                    'vybes'
                ])
                ->where('visible', '=', true)
                ->orderBy('position')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllBySearchPaginated(
        string $search,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Activity::query()
                ->with([
                    'category'
                ])
                ->withCount([
                    'vybes'
                ])
                ->has('category')
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->where('visible', '=', true)
                ->orderBy('position')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     * @param int|null $limit
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getWithGlobalSearchByLimit(
        string $search,
        ?int $limit
    ) : Collection
    {
        try {
            return Activity::query()
                ->withCount([
                    'vybes'
                ])
                ->has('category')
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->orderBy('id', 'desc')
                ->when($limit, function ($query) use ($limit) {
                    $query->limit($limit);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $activitiesIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $activitiesIds
    ) : Collection
    {
        try {
            return Activity::query()
                ->whereIn('id', $activitiesIds)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     * @param array $name
     * @param bool|null $visible
     * @param int|null $position
     *
     * @return Activity|null
     *
     * @throws DatabaseException
     */
    public function store(
        Category $category,
        array $name,
        ?bool $visible = true,
        ?int $position = null
    ) : ?Activity
    {
        try {
            return Activity::query()->create([
                'category_id' => $category->id,
                'name'        => $name,
                'code'        => generateCodeByName($name['en']),
                'visible'     => $visible,
                'position'    => $position ?: Activity::getLastPosition()
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param Category|null $category
     * @param array|null $name
     * @param bool|null $visible
     * @param int|null $position
     *
     * @return Activity
     *
     * @throws DatabaseException
     */
    public function update(
        Activity $activity,
        ?Category $category,
        ?array $name,
        ?bool $visible,
        ?int $position
    ) : Activity
    {
        try {
            $activity->update([
                'category_id' => $category ? $category->id : $activity->category_id,
                'name'        => $name ?: $activity->name,
                'code'        => isset($name['en']) ? generateCodeByName($name['en']) : $activity->code,
                'visible'     => !is_null($visible) ? $visible : $activity->visible,
                'position'    => $position ?: $activity->position,
            ]);

            return $activity;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param int $position
     *
     * @throws DatabaseException
     */
    public function updatePosition(
        Activity $activity,
        int $position
    ) : void
    {
        try {
            $activity->update([
                'position' => $position
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param array $unitsItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function updateUnitPositions(
        Activity $activity,
        array $unitsItems
    ) : Collection
    {
        try {
            $activity->units()->sync(
                $unitsItems,
                false
            );

            return $activity->units;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param Device $device
     *
     * @throws DatabaseException
     */
    public function attachDevice(
        Activity $activity,
        Device $device
    ) : void
    {
        try {
            $activity->devices()->attach(
                $device->id
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param array $devicesIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachDevices(
        Activity $activity,
        array $devicesIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $activity->devices()->sync(
                $devicesIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param Device $device
     *
     * @throws DatabaseException
     */
    public function detachDevice(
        Activity $activity,
        Device $device
    ) : void
    {
        try {
            $activity->devices()->detach([
                $device->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param array $devicesIds
     *
     * @throws DatabaseException
     */
    public function detachDevices(
        Activity $activity,
        array $devicesIds
    ) : void
    {
        try {
            $activity->devices()->detach(
                $devicesIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }


    /**
     * @param Activity $activity
     * @param Platform $platform
     *
     * @throws DatabaseException
     */
    public function attachPlatform(
        Activity $activity,
        Platform $platform
    ) : void
    {
        try {
            $activity->platforms()->attach(
                $platform->id
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param array $platformsIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachPlatforms(
        Activity $activity,
        array $platformsIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $activity->platforms()->sync(
                $platformsIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param Platform $platform
     *
     * @throws DatabaseException
     */
    public function detachPlatform(
        Activity $activity,
        Platform $platform
    ) : void
    {
        try {
            $activity->platforms()->detach([
                $platform->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param array $platformsIds
     *
     * @throws DatabaseException
     */
    public function detachPlatforms(
        Activity $activity,
        array $platformsIds
    ) : void
    {
        try {
            $activity->platforms()->detach(
                $platformsIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param ActivityTag $activityTag
     *
     * @throws DatabaseException
     */
    public function attachActivityTag(
        Activity $activity,
        ActivityTag $activityTag
    ) : void
    {
        try {
            $activity->tags()->attach(
                $activityTag->id
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param array $activityTagsIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachActivityTags(
        Activity $activity,
        array $activityTagsIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $activity->tags()->sync(
                $activityTagsIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param ActivityTag $activityTag
     *
     * @throws DatabaseException
     */
    public function detachActivityTag(
        Activity $activity,
        ActivityTag $activityTag
    ) : void
    {
        try {
            $activity->tags()->detach([
                $activityTag->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param array $activityTagsIds
     *
     * @throws DatabaseException
     */
    public function detachActivityTags(
        Activity $activity,
        array $activityTagsIds
    ) : void
    {
        try {
            $activity->tags()->detach(
                $activityTagsIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param Unit $unit
     * @param bool $visible
     * @param int|null $position
     *
     * @throws DatabaseException
     */
    public function attachUnit(
        Activity $activity,
        Unit $unit,
        bool $visible = true,
        ?int $position = null
    ) : void
    {
        try {
            $activity->units()->attach(
                $unit->id, [
                    'visible'  => $visible
                ]
            );

        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param array $unitsItems
     * @param bool $detaching
     *
     * @throws DatabaseException
     */
    public function attachUnits(
        Activity $activity,
        array $unitsItems,
        bool $detaching = false
    ) : void
    {
        try {
            $activity->units()->sync(
                $unitsItems,
                $detaching
            );

        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param Unit $unit
     *
     * @throws DatabaseException
     */
    public function detachUnit(
        Activity $activity,
        Unit $unit
    ) : void
    {
        try {
            $activity->units()->detach([
                $unit->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     *
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function delete(
        Activity $activity
    ) : ?bool
    {
        try {
            return $activity->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activity.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
