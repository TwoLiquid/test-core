<?php

namespace App\Repositories\Activity;

use App\Exceptions\DatabaseException;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Activity\ActivityTag;
use App\Models\MySql\Category;
use App\Repositories\Activity\Interfaces\ActivityTagRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class ActivityTagRepository
 *
 * @package App\Repositories\Activity
 */
class ActivityTagRepository extends BaseRepository implements ActivityTagRepositoryInterface
{
    /**
     * ActivityTagRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.activityTag.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return ActivityTag|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?ActivityTag
    {
        try {
            return ActivityTag::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return ActivityTag|null
     *
     * @throws DatabaseException
     */
    public function findFullForAdminById(
        ?int $id
    ) : ?ActivityTag
    {
        try {
            return ActivityTag::query()
                ->with([
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'activities' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code',
                            'category_id'
                        ])->with([
                            'category' => function ($query) {
                                $query->select([
                                    'id',
                                    'parent_id',
                                    'name',
                                    'code'
                                ])->with([
                                    'parent' => function ($query) {
                                        $query->select([
                                            'id',
                                            'parent_id',
                                            'name',
                                            'code'
                                        ]);
                                    }
                                ]);
                            }
                        ])->withCount([
                            'vybes' => function ($query) {
                                $query->whereIn('status_id', [
                                    VybeStatusList::getPublishedItem()->id,
                                    VybeStatusList::getPausedItem()->id
                                ]);
                            }
                        ]);
                    }
                ])->withCount([
                    'activities'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
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
            return ActivityTag::query()
                ->with([
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    }
                ])->withCount([
                    'activities'
                ])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category|null $category
     * @param Category|null $subcategory
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllByCategories(
        ?Category $category = null,
        ?Category $subcategory = null
    ) : Collection
    {
        try {
            return ActivityTag::query()
                ->with([
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    }
                ])->withCount([
                    'activities'
                ])
                ->when($category, function ($query) use ($category) {
                    $query->where('category_id', '=', $category->id);
                })
                ->when($subcategory, function ($query) use ($subcategory) {
                    $query->where('subcategory_id', '=', $subcategory->id);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
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
            return ActivityTag::query()
                ->with([
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    }
                ])->withCount([
                    'activities'
                ])
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     * @param Category|null $category
     * @param Category|null $subcategory
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllBySearchAndCategories(
        string $search,
        ?Category $category = null,
        ?Category $subcategory = null
    ) : Collection
    {
        try {
            return ActivityTag::query()
                ->with([
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    }
                ])->withCount([
                    'activities'
                ])
                ->when($category, function ($query) use ($category) {
                    $query->where('category_id', '=', $category->id);
                })
                ->when($subcategory, function ($query) use ($subcategory) {
                    $query->where('subcategory_id', '=', $subcategory->id);
                })
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return ActivityTag::query()
                ->with([
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'activities' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code',
                            'category_id'
                        ])->with([
                            'category' => function ($query) {
                                $query->select([
                                    'id',
                                    'parent_id',
                                    'name',
                                    'code'
                                ])->with([
                                    'parent' => function ($query) {
                                        $query->select([
                                            'id',
                                            'parent_id',
                                            'name',
                                            'code'
                                        ]);
                                    }
                                ]);
                            }
                        ])->withCount([
                            'vybes' => function ($query) {
                                $query->whereIn('status_id', [
                                    VybeStatusList::getPublishedItem()->id,
                                    VybeStatusList::getPausedItem()->id
                                ]);
                            }
                        ]);
                    }
                ])->withCount([
                    'activities'
                ])
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category|null $category
     * @param Category|null $subcategory
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllByCategoriesPaginated(
        ?Category $category = null,
        ?Category $subcategory = null,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return ActivityTag::query()
                ->with([
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'activities' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code',
                            'category_id'
                        ])->with([
                            'category' => function ($query) {
                                $query->select([
                                    'id',
                                    'parent_id',
                                    'name',
                                    'code'
                                ])->with([
                                    'parent' => function ($query) {
                                        $query->select([
                                            'id',
                                            'parent_id',
                                            'name',
                                            'code'
                                        ]);
                                    }
                                ]);
                            }
                        ])->withCount([
                            'vybes' => function ($query) {
                                $query->whereIn('status_id', [
                                    VybeStatusList::getPublishedItem()->id,
                                    VybeStatusList::getPausedItem()->id
                                ]);
                            }
                        ]);
                    }
                ])->withCount([
                    'activities'
                ])
                ->when($category, function ($query) use ($category) {
                    $query->where('category_id', '=', $category->id);
                })
                ->when($subcategory, function ($query) use ($subcategory) {
                    $query->where('subcategory_id', '=', $subcategory->id);
                })
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllBySearchPaginated(
        string $search,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return ActivityTag::query()
                ->with([
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    }
                ])->withCount([
                    'activities'
                ])
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $search
     * @param Category|null $category
     * @param Category|null $subcategory
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllBySearchAndCategoriesPaginated(
        string $search,
        ?Category $category = null,
        ?Category $subcategory = null,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return ActivityTag::query()
                ->with([
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    }
                ])->withCount([
                    'activities'
                ])
                ->when($category, function ($query) use ($category) {
                    $query->where('category_id', '=', $category->id);
                })
                ->when($subcategory, function ($query) use ($subcategory) {
                    $query->where('subcategory_id', '=', $subcategory->id);
                })
                ->whereRaw('lower(name) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $activityTagsIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $activityTagsIds
    ) : Collection
    {
        try {
            return ActivityTag::query()
                ->with([
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    }
                ])->withCount([
                    'activities'
                ])
                ->whereIn('id', $activityTagsIds)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category|null $category
     * @param Category|null $subcategory
     * @param array $name
     * @param bool $visibleInCategory
     * @param bool $visibleInSubcategory
     *
     * @return ActivityTag|null
     *
     * @throws DatabaseException
     */
    public function store(
        ?Category $category,
        ?Category $subcategory,
        array $name,
        bool $visibleInCategory,
        bool $visibleInSubcategory
    ) : ?ActivityTag
    {
        try {
            return ActivityTag::query()->create([
                'category_id'            => $category?->id,
                'subcategory_id'         => $subcategory?->id,
                'name'                   => $name,
                'code'                   => generateCodeByName($name['en']),
                'visible_in_category'    => $visibleInCategory,
                'visible_in_subcategory' => $visibleInSubcategory
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param ActivityTag $activityTag
     * @param Category|null $category
     * @param Category|null $subcategory
     * @param array|null $name
     * @param bool|null $visibleInCategory
     * @param bool|null $visibleInSubcategory
     *
     * @return ActivityTag
     *
     * @throws DatabaseException
     */
    public function update(
        ActivityTag $activityTag,
        ?Category $category,
        ?Category $subcategory,
        ?array $name,
        ?bool $visibleInCategory,
        ?bool $visibleInSubcategory
    ) : ActivityTag
    {
        try {
            $activityTag->update([
                'category_id'            => $category ? $category->id : $activityTag->category_id,
                'subcategory_id'         => $subcategory ? $subcategory->id : $activityTag->subcategory_id,
                'name'                   => $name ?: $activityTag->name,
                'code'                   => isset($name['en']) ? generateCodeByName($name['en']) : $activityTag->code,
                'visible_in_category'    => !is_null($visibleInCategory) ? $visibleInCategory : $activityTag->visible_in_category,
                'visible_in_subcategory' => !is_null($visibleInSubcategory) ? $visibleInSubcategory : $activityTag->visible_in_subcategory
            ]);

            return $activityTag;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param ActivityTag $activityTag
     * @param Activity $activity
     *
     * @throws DatabaseException
     */
    public function attachActivity(
        ActivityTag $activityTag,
        Activity $activity
    ) : void
    {
        try {
            $activityTag->activities()->attach(
                $activity->id
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param ActivityTag $activityTag
     * @param array $activitiesIds
     * @param bool|null $detaching
     * 
     * @throws DatabaseException
     */
    public function attachActivities(
        ActivityTag $activityTag,
        array $activitiesIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $activityTag->activities()->sync(
                $activitiesIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param ActivityTag $activityTag
     * @param Activity $activity
     * 
     * @throws DatabaseException
     */
    public function detachActivity(
        ActivityTag $activityTag,
        Activity $activity
    ) : void
    {
        try {   
            $activityTag->activities()->detach([
                $activity->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param ActivityTag $activityTag
     * @param Activity $activity
     *
     * @throws DatabaseException
     */
    public function detachActivities(
        ActivityTag $activityTag,
        Activity $activity
    ) : void
    {
        try {
            $activityTag->activities()->detach([
                $activity->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param ActivityTag $activityTag
     *
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function delete(
        ActivityTag $activityTag
    ) : ?bool
    {
        try {
            return $activityTag->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/activity/activityTag.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}