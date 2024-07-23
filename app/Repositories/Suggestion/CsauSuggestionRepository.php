<?php

namespace App\Repositories\Suggestion;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Category;
use App\Models\MySql\Unit;
use App\Repositories\BaseRepository;
use App\Repositories\Suggestion\Interfaces\CsauSuggestionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class CsauSuggestionRepository
 *
 * @package App\Repositories\Suggestion
 */
class CsauSuggestionRepository extends BaseRepository implements CsauSuggestionRepositoryInterface
{
    /**
     * CsauSuggestionRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.csauSuggestion.cacheTime');
        $this->perPage = config('repositories.csauSuggestion.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return CsauSuggestion|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?CsauSuggestion
    {
        try {
            return CsauSuggestion::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $id
     *
     * @return CsauSuggestion|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?string $id
    ) : ?CsauSuggestion
    {
        try {
            return CsauSuggestion::query()
                ->with([
                    'vybePublishRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'type_id',
                            'devices_ids'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'title',
                                    'user_id',
                                    'type_id'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'language_id',
                                            'username'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'vybeChangeRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'type_id',
                            'devices_ids'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'title',
                                    'user_id',
                                    'type_id'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
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
                    'unit' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    }
                ])
                ->where('_id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return CsauSuggestion|null
     *
     * @throws DatabaseException
     */
    public function findFirstForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : ?CsauSuggestion
    {
        try {
            return CsauSuggestion::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return CsauSuggestion|null
     *
     * @throws DatabaseException
     */
    public function findFirstForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : ?CsauSuggestion
    {
        try {
            return CsauSuggestion::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
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
            return Cache::remember('csauSuggestionRequests.all.count', $this->cacheTime,
                function () {
                    return CsauSuggestion::query()
                        ->where('status_id', '=', RequestStatusList::getPendingItem()->id)
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : Collection
    {
        try {
            return CsauSuggestion::query()
                ->with([
                    'vybePublishRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'title',
                                    'user_id'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
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
                    'unit' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    }
                ])
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : Collection
    {
        try {
            return CsauSuggestion::query()
                ->with([
                    'vybeChangeRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'title',
                                    'user_id'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
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
                    'unit' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    }
                ])
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $vybeVersion
     * @param string|null $vybeTitle
     * @param array|null $categoriesIds
     * @param array|null $subcategoriesIds
     * @param array|null $activitiesIds
     * @param array|null $unitTypesIds
     * @param array|null $unitsIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllPending(
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $vybeVersion,
        ?string $vybeTitle,
        ?array $categoriesIds,
        ?array $subcategoriesIds,
        ?array $activitiesIds,
        ?array $unitTypesIds,
        ?array $unitsIds
    ) : Collection
    {
        try {
            return CsauSuggestion::query()
                ->with([
                    'vybePublishRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'type_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'title',
                                    'user_id',
                                    'type_id',
                                    'version',
                                    'period_id',
                                    'user_count'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username',
                                            'language_id'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'vybeChangeRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'type_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'title',
                                    'user_id',
                                    'type_id',
                                    'version',
                                    'period_id',
                                    'user_count'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username',
                                            'language_id'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
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
                    'unit' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    }
                ])
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', $dateFrom);
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', $dateTo);
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('vybePublishRequest.vybe.user', function($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username . '%');
                    })->orWhereHas('vybeChangeRequest.vybe.user', function($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username . '%');
                    });
                })
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->orWhereHas('vybePublishRequest.vybe',
                        function ($query) use ($vybeVersion) {
                            $query->where('version', '=', (int) $vybeVersion);
                        }
                    )->orWhereHas('vybeChangeRequest.vybe',
                        function ($query) use ($vybeVersion) {
                            $query->where('version', '=', (int) $vybeVersion);
                        }
                    );
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->orWhereHas('vybePublishRequest.vybe',
                        function ($query) use ($vybeTitle) {
                            $query->where('title', 'LIKE', '%' . $vybeTitle . '%');
                        }
                    )->orWhereHas('vybeChangeRequest.vybe',
                        function ($query) use ($vybeTitle) {
                            $query->where('title', 'LIKE', '%' . $vybeTitle . '%');
                        }
                    );
                })
                ->when($categoriesIds, function ($query) use ($categoriesIds) {
                    $query->orWhereHas('category', function ($query) use ($categoriesIds) {
                        $query->whereIn('id', $categoriesIds);
                    });
                })
                ->when($subcategoriesIds, function ($query) use ($subcategoriesIds) {
                    $query->orWhereHas('subcategory', function ($query) use ($subcategoriesIds) {
                        $query->whereIn('id', $subcategoriesIds);
                    });
                })
                ->when($activitiesIds, function ($query) use ($activitiesIds) {
                    $query->orWhereHas('activity', function ($query) use ($activitiesIds) {
                        $query->whereIn('id', $activitiesIds);
                    });
                })
                ->when($unitTypesIds, function ($query) use ($unitTypesIds) {
                    $query->orWhereHas('unit', function ($query) use ($unitTypesIds) {
                        $query->whereIn('type_id', $unitTypesIds);
                    });
                })
                ->when($unitsIds, function ($query) use ($unitsIds) {
                    $query->orWhereHas('unit', function ($query) use ($unitsIds) {
                        $query->whereIn('id', $unitsIds);
                    });
                })
                ->where('status_id', '=', RequestStatusList::getPendingItem()->id)
                ->orderBy('_id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param string|null $username
     * @param int|null $vybeVersion
     * @param string|null $vybeTitle
     * @param array|null $categoriesIds
     * @param array|null $subcategoriesIds
     * @param array|null $activitiesIds
     * @param array|null $unitTypesIds
     * @param array|null $unitsIds
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPendingPaginated(
        ?string $dateFrom,
        ?string $dateTo,
        ?string $username,
        ?int $vybeVersion,
        ?string $vybeTitle,
        ?array $categoriesIds,
        ?array $subcategoriesIds,
        ?array $activitiesIds,
        ?array $unitTypesIds,
        ?array $unitsIds,
        ?int $page = 1,
        ?int $perPage = 18
    ) : LengthAwarePaginator
    {
        try {
            return CsauSuggestion::query()
                ->with([
                    'vybePublishRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'type_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'title',
                                    'user_id',
                                    'type_id',
                                    'version',
                                    'period_id',
                                    'user_count'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username',
                                            'language_id'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'vybeChangeRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'type_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'title',
                                    'user_id',
                                    'type_id',
                                    'version',
                                    'period_id',
                                    'user_count'
                                ])->with([
                                    'user' => function ($query) {
                                        $query->select([
                                            'id',
                                            'auth_id',
                                            'username',
                                            'language_id'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    },
                    'subcategory' => function ($query) {
                        $query->select([
                            'id',
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
                    'unit' => function ($query) {
                        $query->select([
                            'id',
                            'code',
                            'name'
                        ]);
                    }
                ])
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->where('created_at', '>=', $dateFrom);
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->where('created_at', '<=', $dateTo);
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('vybePublishRequest.vybe.user', function($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username . '%');
                    })->orWhereHas('vybeChangeRequest.vybe.user', function($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username . '%');
                    });
                })
                ->when($vybeVersion, function ($query) use ($vybeVersion) {
                    $query->orWhereHas('vybePublishRequest.vybe',
                        function ($query) use ($vybeVersion) {
                            $query->where('version', '=', (int) $vybeVersion);
                        }
                    )->orWhereHas('vybeChangeRequest.vybe',
                        function ($query) use ($vybeVersion) {
                            $query->where('version', '=', (int) $vybeVersion);
                        }
                    );
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->orWhereHas('vybePublishRequest.vybe',
                        function ($query) use ($vybeTitle) {
                            $query->where('title', 'LIKE', '%' . $vybeTitle . '%');
                        }
                    )->orWhereHas('vybeChangeRequest.vybe',
                        function ($query) use ($vybeTitle) {
                            $query->where('title', 'LIKE', '%' . $vybeTitle . '%');
                        }
                    );
                })
                ->when($categoriesIds, function ($query) use ($categoriesIds) {
                    $query->orWhereHas('category', function ($query) use ($categoriesIds) {
                        $query->whereIn('id', $categoriesIds);
                    });
                })
                ->when($subcategoriesIds, function ($query) use ($subcategoriesIds) {
                    $query->orWhereHas('subcategory', function ($query) use ($subcategoriesIds) {
                        $query->whereIn('id', $subcategoriesIds);
                    });
                })
                ->when($activitiesIds, function ($query) use ($activitiesIds) {
                    $query->orWhereHas('activity', function ($query) use ($activitiesIds) {
                        $query->whereIn('id', $activitiesIds);
                    });
                })
                ->when($unitTypesIds, function ($query) use ($unitTypesIds) {
                    $query->orWhereHas('unit', function ($query) use ($unitTypesIds) {
                        $query->whereIn('type_id', $unitTypesIds);
                    });
                })
                ->when($unitsIds, function ($query) use ($unitsIds) {
                    $query->orWhereHas('unit', function ($query) use ($unitsIds) {
                        $query->whereIn('id', $unitsIds);
                    });
                })
                ->where('status_id', '=', RequestStatusList::getPendingItem()->id)
                ->orderBy('_id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest|null $vybePublishRequest
     * @param VybeChangeRequest|null $vybeChangeRequest
     * @param Category|null $category
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param string|null $subcategorySuggestion
     * @param Activity|null $activity
     * @param string|null $activitySuggestion
     * @param Unit|null $unit
     * @param string|null $unitSuggestion
     *
     * @return CsauSuggestion|null
     *
     * @throws DatabaseException
     */
    public function store(
        ?VybePublishRequest $vybePublishRequest,
        ?VybeChangeRequest $vybeChangeRequest,
        ?Category $category,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?string $subcategorySuggestion,
        ?Activity $activity,
        ?string $activitySuggestion,
        ?Unit $unit,
        ?string $unitSuggestion
    ) : ?CsauSuggestion
    {
        try {
            return CsauSuggestion::query()->create([
                'vybe_publish_request_id' => $vybePublishRequest?->_id,
                'vybe_change_request_id'  => $vybeChangeRequest?->_id,
                'category_id'             => $category?->id,
                'category_suggestion'     => $categorySuggestion,
                'category_status_id'      => $category ?
                    RequestFieldStatusList::getAcceptedItem()->id :
                    RequestFieldStatusList::getPendingItem()->id,
                'subcategory_id'          => $subcategory?->id,
                'subcategory_suggestion'  => $subcategorySuggestion,
                'subcategory_status_id'   => $subcategory ?
                    RequestFieldStatusList::getAcceptedItem()->id :
                    ($subcategorySuggestion ?
                        RequestFieldStatusList::getPendingItem()->id :
                        null
                    ),
                'activity_id'             => $activity?->id,
                'activity_suggestion'     => $activitySuggestion,
                'activity_status_id'      => $activity ?
                    RequestFieldStatusList::getAcceptedItem()->id :
                    RequestFieldStatusList::getPendingItem()->id,
                'unit_id'                 => $unit?->id,
                'unit_suggestion'         => $unitSuggestion,
                'unit_duration'           => null,
                'unit_status_id'          => $unit ?
                    RequestFieldStatusList::getAcceptedItem()->id :
                    RequestFieldStatusList::getPendingItem()->id,
                'visible'                 => true,
                'status_id'               => RequestStatusList::getPendingItem()->id,
                'admin_id'                => null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     * @param Category $category
     *
     * @return CsauSuggestion
     *
     * @throws DatabaseException
     */
    public function updateCategory(
        CsauSuggestion $csauSuggestion,
        Category $category
    ) : CsauSuggestion
    {
        try {
            $csauSuggestion->update([
                'category_id' => $category->id
            ]);

            return $csauSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param Category $category
     *
     * @throws DatabaseException
     */
    public function acceptCategoryForVybePublishRequest(
        VybePublishRequest $vybePublishRequest,
        Category $category
    ) : void
    {
        try {
            CsauSuggestion::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->update([
                    'category_id'        => $category->id,
                    'category_status_id' => RequestFieldStatusList::getAcceptedItem()->id,
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Category $category
     *
     * @throws DatabaseException
     */
    public function acceptCategoryForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest,
        Category $category
    ) : void
    {
        try {
            CsauSuggestion::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->update([
                    'category_id'        => $category->id,
                    'category_status_id' => RequestFieldStatusList::getAcceptedItem()->id,
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @throws DatabaseException
     */
    public function declineCategoryForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : void
    {
        try {
            CsauSuggestion::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->update([
                    'category_status_id' => RequestFieldStatusList::getDeclinedItem()->id
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @throws DatabaseException
     */
    public function declineCategoryForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : void
    {
        try {
            CsauSuggestion::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->update([
                    'category_status_id' => RequestFieldStatusList::getDeclinedItem()->id
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     * @param RequestFieldStatusListItem $requestFieldStatusListItem
     *
     * @return CsauSuggestion
     *
     * @throws DatabaseException
     */
    public function updateCategoryStatus(
        CsauSuggestion $csauSuggestion,
        RequestFieldStatusListItem $requestFieldStatusListItem
    ) : CsauSuggestion
    {
        try {
            $csauSuggestion->update([
                'category_status_id' => $requestFieldStatusListItem->id
            ]);

            return $csauSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     * @param Category $subcategory
     *
     * @return CsauSuggestion
     *
     * @throws DatabaseException
     */
    public function updateSubcategory(
        CsauSuggestion $csauSuggestion,
        Category $subcategory
    ) : CsauSuggestion
    {
        try {
            $csauSuggestion->update([
                'subcategory_id' => $subcategory->id
            ]);

            return $csauSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param Category $subcategory
     *
     * @throws DatabaseException
     */
    public function acceptSubcategoryForVybePublishRequest(
        VybePublishRequest $vybePublishRequest,
        Category $subcategory
    ) : void
    {
        try {
            CsauSuggestion::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->update([
                    'subcategory_id'        => $subcategory->id,
                    'subcategory_status_id' => RequestFieldStatusList::getAcceptedItem()->id
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Category $subcategory
     *
     * @throws DatabaseException
     */
    public function acceptSubcategoryForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest,
        Category $subcategory
    ) : void
    {
        try {
            CsauSuggestion::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->update([
                    'subcategory_id'        => $subcategory->id,
                    'subcategory_status_id' => RequestFieldStatusList::getAcceptedItem()->id
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @throws DatabaseException
     */
    public function declineSubcategoryForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : void
    {
        try {
            CsauSuggestion::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->update([
                    'subcategory_status_id' => RequestFieldStatusList::getDeclinedItem()->id
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @throws DatabaseException
     */
    public function declineSubcategoryForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : void
    {
        try {
            CsauSuggestion::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->update([
                    'subcategory_status_id' => RequestFieldStatusList::getDeclinedItem()->id
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     * @param RequestFieldStatusListItem $requestFieldStatusListItem
     *
     * @return CsauSuggestion
     *
     * @throws DatabaseException
     */
    public function updateSubcategoryStatus(
        CsauSuggestion $csauSuggestion,
        RequestFieldStatusListItem $requestFieldStatusListItem
    ) : CsauSuggestion
    {
        try {
            $csauSuggestion->update([
                'subcategory_status_id' => $requestFieldStatusListItem->id
            ]);

            return $csauSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     * @param Activity $activity
     *
     * @return CsauSuggestion
     *
     * @throws DatabaseException
     */
    public function updateActivity(
        CsauSuggestion $csauSuggestion,
        Activity $activity
    ) : CsauSuggestion
    {
        try {
            $csauSuggestion->update([
                'activity_id' => $activity->id
            ]);

            return $csauSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param Activity $activity
     *
     * @throws DatabaseException
     */
    public function acceptActivityForVybePublishRequest(
        VybePublishRequest $vybePublishRequest,
        Activity $activity
    ) : void
    {
        try {
            CsauSuggestion::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->update([
                    'activity_id'        => $activity->id,
                    'activity_status_id' => RequestFieldStatusList::getAcceptedItem()->id
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Activity $activity
     *
     * @throws DatabaseException
     */
    public function acceptActivityForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest,
        Activity $activity
    ) : void
    {
        try {
            CsauSuggestion::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->update([
                    'activity_id'        => $activity->id,
                    'activity_status_id' => RequestFieldStatusList::getAcceptedItem()->id
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @throws DatabaseException
     */
    public function declineActivityForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : void
    {
        try {
            CsauSuggestion::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->update([
                    'activity_status_id' => RequestFieldStatusList::getDeclinedItem()->id
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @throws DatabaseException
     */
    public function declineActivityForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : void
    {
        try {
            CsauSuggestion::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->update([
                    'activity_status_id' => RequestFieldStatusList::getDeclinedItem()->id
                ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     * @param RequestFieldStatusListItem $requestFieldStatusListItem
     *
     * @return CsauSuggestion
     *
     * @throws DatabaseException
     */
    public function updateActivityStatus(
        CsauSuggestion $csauSuggestion,
        RequestFieldStatusListItem $requestFieldStatusListItem
    ) : CsauSuggestion
    {
        try {
            $csauSuggestion->update([
                'activity_status_id' => $requestFieldStatusListItem->id
            ]);

            return $csauSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     * @param Unit $unit
     *
     * @return CsauSuggestion
     *
     * @throws DatabaseException
     */
    public function updateUnit(
        CsauSuggestion $csauSuggestion,
        Unit $unit
    ) : CsauSuggestion
    {
        try {
            $csauSuggestion->update([
                'unit_id'        => $unit->id,
                'unit_status_id' => RequestFieldStatusList::getAcceptedItem()->id
            ]);

            return $csauSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     * @param RequestFieldStatusListItem $requestFieldStatusListItem
     *
     * @return CsauSuggestion
     *
     * @throws DatabaseException
     */
    public function updateUnitStatus(
        CsauSuggestion $csauSuggestion,
        RequestFieldStatusListItem $requestFieldStatusListItem
    ) : CsauSuggestion
    {
        try {
            $csauSuggestion->update([
                'unit_status_id' => $requestFieldStatusListItem->id
            ]);

            return $csauSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return CsauSuggestion
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        CsauSuggestion $csauSuggestion,
        RequestStatusListItem $requestStatusListItem
    ) : CsauSuggestion
    {
        try {
            $csauSuggestion->update([
                'status_id' => $requestStatusListItem->id
            ]);

            return $csauSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     * @param Admin $admin
     *
     * @return CsauSuggestion
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        CsauSuggestion $csauSuggestion,
        Admin $admin
    ) : CsauSuggestion
    {
        try {
            $csauSuggestion->update([
                'admin_id' => $admin->id
            ]);

            return $csauSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        CsauSuggestion $csauSuggestion
    ) : bool
    {
        try {
            return $csauSuggestion->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
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
    public function deletePendingForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : bool
    {
        try {
            return CsauSuggestion::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->where('status_id', '=', RequestStatusList::getPendingItem()->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
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
    public function deletePendingForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : bool
    {
        try {
            return CsauSuggestion::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->where('status_id', '=', RequestStatusList::getPendingItem()->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/csauSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
