<?php

namespace App\Repositories\Category;

use App\Exceptions\DatabaseException;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MySql\Category;
use App\Repositories\BaseRepository;
use App\Repositories\Category\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class CategoryRepository
 *
 * @package App\Repositories\Category
 */
class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * CategoryRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.category.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Category|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Category
    {
        try {
            return Category::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return Category|null
     *
     * @throws DatabaseException
     */
    public function findFullForAdminById(
        ?int $id
    ) : ?Category
    {
        try {
            return Category::query()
                ->select([
                    'id',
                    'code',
                    'name',
                    'visible',
                    'position'
                ])
                ->with([
                    'subcategories' => function ($query) {
                        $query->select([
                            'id',
                            'parent_id',
                            'code',
                            'name',
                            'visible',
                            'position'
                        ])->with([
                            'activities' => function ($query) {
                                $query->select([
                                    'id',
                                    'category_id',
                                    'code',
                                    'name',
                                    'visible'
                                ])->with([
                                    'units' => function ($query) {
                                        $query->select([
                                            'id',
                                            'activity_id',
                                            'code',
                                            'name',
                                            'duration'
                                        ]);
                                    },
                                    'tags' => function ($query) {
                                        $query->select([
                                            'id',
                                            'code',
                                            'name'
                                        ])->withCount([
                                            'activities'
                                        ]);
                                    }
                                ])->withCount(
                                    'vybes'
                                );
                            }
                        ])->withCount(
                            'activities',
                            'vybes'
                        )
                        ->orderBy(
                            'position'
                        );
                    },
                    'activities' => function ($query) {
                        $query->select([
                            'id',
                            'category_id',
                            'code',
                            'name',
                            'visible',
                            'position'
                        ])->with([
                            'units' => function ($query) {
                                $query->select([
                                    'id',
                                    'activity_id',
                                    'code',
                                    'name',
                                    'duration',
                                    'activity_unit.visible',
                                    'activity_unit.position'
                                ]);
                                /* ->orderBy(
                                    'position'
                                ); */
                            },
                            'tags' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ])->withCount([
                                    'activities'
                                ]);
                            },
                            'devices',
                            'platforms'
                        ])->withCount(
                            'vybes'
                        )
                        ->orderBy(
                            'position'
                        );
                    },
                    'categoryTags' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name',
                            'category_id',
                            'visible_in_category'
                        ])
                        ->withCount(
                            'activities'
                        )
                        ->where('visible_in_category', '=', true);
                    },
                    'subcategoryTags' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name',
                            'subcategory_id',
                            'visible_in_subcategory'
                        ])
                        ->withCount(
                            'activities'
                        )
                        ->where('visible_in_subcategory', '=', true);
                    }
                ])
                ->withCount([
                    'subcategories',
                    'activities',
                    'vybes'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $code
     *
     * @return Category|null
     *
     * @throws DatabaseException
     */
    public function findFullByCode(
        ?string $code
    ) : ?Category
    {
        try {
            return Category::query()
                ->select([
                    'id',
                    'parent_id',
                    'code',
                    'name',
                    'visible',
                    'position'
                ])
                ->with([
                    'subcategories' => function ($query) {
                        $query->select([
                            'id',
                            'parent_id',
                            'code',
                            'name',
                            'visible',
                            'position'
                        ])
                        ->where('visible', '=', 1)
                        ->withCount(
                            'vybes'
                        );
                    },
                    'categoryTags' => function ($query) {
                        $query->select([
                            'id',
                            'category_id',
                            'name',
                            'code',
                            'visible_in_category'
                        ])
                        ->where('visible_in_category', '=', 1);
                    }
                ])
                ->withCount(
                    'vybes'
                )
                ->where('code', '=', $code)
                ->where('visible', '=', 1)
                ->whereNull('parent_id')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $code
     *
     * @return Category|null
     *
     * @throws DatabaseException
     */
    public function findFullSubcategoryByCode(
        ?string $code
    ) : ?Category
    {
        try {
            return Category::query()
                ->select([
                    'id',
                    'parent_id',
                    'code',
                    'name',
                    'visible',
                    'position'
                ])
                ->with([
                    'parent' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name',
                            'visible'
                        ])
                        ->with([
                            'subcategories' => function ($query) {
                                $query->select([
                                    'id',
                                    'parent_id',
                                    'code',
                                    'name',
                                    'visible',
                                    'position'
                                ])
                                ->where('visible', '=', 1);
                            }
                        ])
                        ->withCount(
                            'vybes'
                        );
                    },
                    'activities' => function ($query) {
                        $query->select([
                            'id',
                            'category_id',
                            'code',
                            'name',
                            'visible'
                        ])
                        ->with([
                            'units' => function ($query) {
                                $query->select([
                                    'id',
                                    'activity_id',
                                    'code',
                                    'name',
                                    'duration'
                                ])
                                ->where('units.visible', '=', true)
                                ->where('activity_unit.visible', '=', true);
                            }
                        ])
                        ->where('visible', '=', 1)
                        ->withCount(
                            'vybes'
                        );
                    },
                    'subcategoryTags' => function ($query) {
                        $query->select([
                            'id',
                            'subcategory_id',
                            'name',
                            'code',
                            'visible_in_subcategory'
                        ])
                        ->where('visible_in_subcategory', '=', 1);
                    }
                ])
                ->withCount(
                    'vybes'
                )
                ->where('code', '=', $code)
                ->where('visible', '=', 1)
                ->whereNotNull('parent_id')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $name
     *
     * @return Category|null
     *
     * @throws DatabaseException
     */
    public function findByName(
        string $name
    ) : ?Category
    {
        try {
            return Category::query()
                ->whereNull('parent_id')
                ->where('name->en', '=', trim($name))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     * @param string $name
     *
     * @return Category|null
     *
     * @throws DatabaseException
     */
    public function findSubcategoryByName(
        Category $category,
        string $name
    ) : ?Category
    {
        try {
            return Category::query()
                ->where('parent_id', '=', $category->id)
                ->where('name', '=', trim($name))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
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
            return Category::query()
                ->whereNull('parent_id')
                ->where('visible', '=', true)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForAdmin() : Collection
    {
        try {
            return Category::query()
                ->with([
                    'subcategories' => function ($query) {
                        $query->with([
                            'activities' => function ($query) {
                                $query->withCount(
                                    'vybes'
                                );
                            }
                        ])->withCount(
                            'activities',
                            'vybes'
                        );
                    }])
                ->with([
                    'activities' => function ($query) {
                        $query->withCount(
                            'vybes'
                        );
                    }
                ])
                ->withCount([
                    'subcategories',
                    'activities',
                    'vybes'
                ])
                ->whereNull('parent_id')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
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
            return Category::query()
                ->whereNull('parent_id')
                ->where('visible', '=', true)
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
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
    public function getAllForAdminPaginated(
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Category::query()
                ->with([
                    'subcategories' => function ($query) {
                        $query->with([
                            'activities' => function ($query) {
                                $query->withCount(
                                    'vybes'
                                );
                            }
                        ])->withCount(
                            'activities'
                        );
                    }])
                ->with([
                    'activities' => function ($query) {
                        $query->withCount(
                            'vybes'
                        );
                    }
                ])
                ->withCount([
                    'subcategories',
                    'activities'
                ])
                ->whereNull('parent_id')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
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
            return Category::query()
                ->where('parent_id', '=', $category->id)
                ->where('visible', '=', true)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $categoriesIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByCategoriesIds(
        array $categoriesIds
    ) : Collection
    {
        try {
            return Category::query()
                ->whereIn('parent_id', $categoriesIds)
                ->where('visible', '=', true)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getParentCategories() : Collection
    {
        try {
            return Category::query()
                ->whereNull('parent_id')
                ->where('visible', '=', true)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
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
    public function getAllSubcategoriesPaginated(
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Category::query()
                ->with([
                    'activities' => function ($query) {
                        $query->withCount(
                            'vybes'
                        );
                    }
                ])
                ->withCount([
                    'activities'
                ])
                ->whereNotNull('parent_id')
                ->where('visible', '=', true)
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllSubcategories() : Collection
    {
        try {
            return Category::query()
                ->with([
                    'activities' => function ($query) {
                        $query->withCount(
                            'vybes'
                        );
                    }
                ])
                ->withCount([
                    'activities',
                    'vybes'
                ])
                ->whereNotNull('parent_id')
                ->where('visible', '=', true)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     * 
     * @throws DatabaseException
     */
    public function getCategoriesForNavbar() : Collection
    {
        try {
            return Category::query()
                ->whereNull('parent_id')
                ->where('visible', '=', true)
                ->limit(5)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     * 
     * @throws DatabaseException
     */
    public function getCategoriesForHomeWithVybes() : Collection
    {
        try {
            return Category::query()
                ->select([
                    'id',
                    'parent_id',
                    'visible',
                    'code',
                    'name',
                    'position'
                ])
                ->with([
                    'parent' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name',
                            'position'
                        ]);
                    },
                    'vybes' => function ($query) {
                        $query->select([
                            'vybes.id',
                            'user_id',
                            'type_id',
                            'activity_id',
                            'type_id',
                            'access_id',
                            'period_id',
                            'status_id',
                            'title',
                            'rating',
                            'images_ids',
                            'videos_ids'
                        ])->with([
                            'user' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'category_id',
                                    'name',
                                    'code'
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
                                            'type_id',
                                            'name',
                                            'code',
                                            'duration',
                                            'visible'
                                        ]);
                                    }
                                ]);
                            }
                        ])->limit(12);
                    }
                ])
                ->whereHas('vybes', function ($query) {
                    $query->where('status_id', '=', VybeStatusList::getPublishedItem()->id);
                })
                ->where('visible', '=', true)
                ->limit(2)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category|null $parentCategory
     * @param array $name
     * @param bool|null $visible
     * @param int|null $position
     *
     * @return Category|null
     *
     * @throws DatabaseException
     */
    public function store(
        ?Category $parentCategory,
        array $name,
        ?bool $visible = true,
        ?int $position = null
    ) : ?Category
    {
        try {
            return Category::query()->create([
                'parent_id' => $parentCategory?->id,
                'name'      => $name,
                'code'      => generateCodeByName($name['en']),
                'visible'   => $visible,
                'position'  => $position ?: Category::getLastPosition()
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     * @param Category|null $parentCategory
     * @param array|null $name
     * @param bool|null $visible
     * @param int|null $position
     *
     * @return Category
     *
     * @throws DatabaseException
     */
    public function update(
        Category $category,
        ?Category $parentCategory,
        ?array $name,
        ?bool $visible,
        ?int $position
    ) : Category
    {
        try {
            $category->update([
                'parent_id' => $parentCategory ? $parentCategory->id : $category->parent_id,
                'name'      => $name ?: $category->name,
                'code'      => isset($name['en']) ? generateCodeByName($name['en']) : $category->code,
                'visible'   => !is_null($visible) ? $visible : $category->visible,
                'position'  => $position ?: $category->position
            ]);

            return $category;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     * @param int $position
     *
     * @throws DatabaseException
     */
    public function updatePosition(
        Category $category,
        int $position
    ) : void
    {
        try {
            $category->update([
                'position' => $position
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Category $category
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Category $category
    ) : bool
    {
        try {
            return $category->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/category.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}