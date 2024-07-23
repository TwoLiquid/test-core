<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Vybe\Access\VybeAccessList;
use App\Lists\Vybe\Access\VybeAccessListItem;
use App\Lists\Vybe\Period\VybePeriodListItem;
use App\Lists\Vybe\Showcase\VybeShowcaseListItem;
use App\Lists\Vybe\Sort\VybeSortList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Lists\Vybe\Step\VybeStepList;
use App\Lists\Vybe\Step\VybeStepListItem;
use App\Lists\Vybe\Type\VybeTypeListItem;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitListItem;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptListItem;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Category;
use App\Models\MySql\Device;
use App\Models\MySql\Platform;
use App\Models\MySql\Unit;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Models\MySql\Vybe\VybeRatingVote;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;
use Exception;

/**
 * Class VybeRepository
 *
 * @package App\Repositories\Vybe
 */
class VybeRepository extends BaseRepository implements VybeRepositoryInterface
{
    /**
     * VybeRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybe.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Vybe|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Vybe
    {
        try {
            return Vybe::query()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username',
                            'account_status_id'
                        ]);
                    },
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
                                    'parent_id',
                                    'name',
                                    'code'
                                ])->with([
                                    'parent' => function ($query) {
                                        $query->select([
                                            'id',
                                            'name',
                                            'code'
                                        ]);
                                    }
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
                            'city_place_id',
                            'price',
                            'description',
                            'enabled'
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
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
                                ]);
                            },
                            'cityPlace.timezone.offsets'
                        ]);
                    },
                    'devices' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    }
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return Vybe|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?int $id
    ) : ?Vybe
    {
        try {
            return Vybe::query()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'account_status_id',
                            'current_city_place_id',
                            'timezone_id',
                            'language_id',
                            'gender_id',
                            'referred_user_id',
                            'suspend_admin_id',
                            'avatar_id',
                            'voice_sample_id',
                            'username',
                            'email',
                            'suspend_reason',
                            'signed_up_at'
                        ])->with([
                            'timezone.offsets',
                            'currentCityPlace.timezone.offsets',
                            'referredUser' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'suspendAdmin' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'last_name',
                                    'first_name'
                                ]);
                            }
                        ]);
                    },
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
                                    'parent_id',
                                    'name',
                                    'code'
                                ])->with([
                                    'parent' => function ($query) {
                                        $query->select([
                                            'id',
                                            'name',
                                            'code'
                                        ]);
                                    }
                                ]);
                            },
                            'devices',
                            'platforms'
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
                            'same_location',
                            'city_place_id',
                            'price',
                            'description',
                            'enabled'
                        ])->with([
                            'unit' => function ($query) {
                                $query->select([
                                    'id',
                                    'type_id',
                                    'name',
                                    'code',
                                    'duration',
                                ]);
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code',
                                    'voice_chat',
                                    'visible_in_voice_chat',
                                    'video_chat',
                                    'visible_in_video_chat'
                                ]);
                            },
                            'support' => function ($query) {
                                $query->select([
                                    'id',
                                    'appearance_case_id',
                                    'unit_suggestion'
                                ]);
                            },
                            'cityPlace.timezone.offsets'
                        ]);
                    },
                    'devices' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'orderItems' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'timeslot_id'
                        ])->with([
                            'timeslot' => function ($query) {
                                $query->withCount([
                                    'users'
                                ]);
                            }
                        ])->orderBy(
                            'created_at',
                            'desc'
                        )->limit(1);
                    },
                    'versions' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'type',
                            'version',
                            'created_at'
                        ]);
                    },
                    'support' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'category_id',
                            'category_suggestion',
                            'support_id',
                            'subcategory_id',
                            'subcategory_suggestion',
                            'activity_suggestion',
                            'device_suggestion',
                            'devices_ids'
                        ])->with([
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
                            }
                        ]);
                    },
                    'publishRequest' => function ($query) {
                        $query->with([
                            'appearanceCases' => function ($query) {
                                $query->with([
                                    'unit',
                                    'cityPlace',
                                    'csauSuggestion'
                                ]);
                            },
                            'schedules'
                        ]);
                    },
                    'changeRequest' => function ($query) {
                        $query->with([
                            'category',
                            'previousCategory',
                            'subcategory',
                            'previousSubcategory',
                            'activity',
                            'previousActivity',
                            'appearanceCases' => function ($query) {
                                $query->with([
                                    'unit',
                                    'previousUnit',
                                    'cityPlace',
                                    'previousCityPlace',
                                    'csauSuggestion'
                                ]);
                            },
                            'schedules'
                        ]);
                    },
                    'unpublishRequest',
                    'unsuspendRequest',
                    'deletionRequest'
                ])
                ->withCount([
                    'ratingVotes'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
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
            return Vybe::query()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username'
                        ]);
                    },
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
                                    'name',
                                    'code'
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
                                    'type_id',
                                    'name',
                                    'code',
                                    'duration',
                                    'visible'
                                ]);
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    }
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
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
            return Vybe::query()
                ->has('activity')
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username'
                        ]);
                    },
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
                                    'name',
                                    'code'
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
                                    'type_id',
                                    'name',
                                    'code',
                                    'duration',
                                    'visible'
                                ]);
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    }
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
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
            return Vybe::query()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username'
                        ]);
                    },
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
                                    'name',
                                    'code'
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
                                    'type_id',
                                    'name',
                                    'code',
                                    'duration',
                                    'visible'
                                ]);
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    }
                ])
                ->whereRaw('lower(title) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
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
            return Vybe::query()
                ->has('activity')
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username'
                        ]);
                    },
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
                                    'name',
                                    'code'
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
                                    'type_id',
                                    'name',
                                    'code',
                                    'duration',
                                    'visible'
                                ]);
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    }
                ])
                ->whereRaw('lower(title) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
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
            return Vybe::query()
                ->select([
                    'id',
                    'user_id',
                    'activity_id',
                    'type_id',
                    'status_id',
                    'title'
                ])
                ->with([
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
                            'code',
                            'name'
                        ])->with([
                            'category' => function ($query) {
                                $query->select([
                                    'id',
                                    'parent_id',
                                    'code',
                                    'name'
                                ])->with([
                                    'parent' => function ($query) {
                                        $query->select([
                                            'id',
                                            'parent_id',
                                            'code',
                                            'name'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'appearanceCases' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'appearance_id',
                            'unit_id',
                            'price'
                        ])->with([
                            'unit' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    }
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $vybesIds
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function getStatusesByIdsCount(
        array $vybesIds
    ) : Vybe
    {
        try {
            return Vybe::query()
                ->whereIn('id', $vybesIds)
                ->selectRaw('sum(case when status_id = 1 then 1 else 0 end) as draft')
                ->selectRaw('sum(case when status_id = 2 then 1 else 0 end) as published')
                ->selectRaw('sum(case when status_id = 3 then 1 else 0 end) as paused')
                ->selectRaw('sum(case when status_id = 4 then 1 else 0 end) as suspended')
                ->selectRaw('sum(case when status_id = 5 then 1 else 0 end) as deleted')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $vybeId
     * @param array|null $categoriesIds
     * @param array|null $subcategoriesIds
     * @param array|null $activitiesIds
     * @param array|null $typesIds
     * @param array|null $usersIds
     * @param string|null $vybeTitle
     * @param float|null $price
     * @param array|null $unitsIds
     * @param array|null $statusesIds
     * @param string|null $sortBy
     * @param string|null $sortOrder
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        ?int $vybeId = null,
        ?array $categoriesIds = null,
        ?array $subcategoriesIds = null,
        ?array $activitiesIds = null,
        ?array $typesIds = null,
        ?array $usersIds = null,
        ?string $vybeTitle = null,
        ?float $price = null,
        ?array $unitsIds = null,
        ?array $statusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null
    ) : Collection
    {
        try {
            return Vybe::query()
                ->select([
                    'id',
                    'user_id',
                    'activity_id',
                    'type_id',
                    'status_id',
                    'title'
                ])
                ->with([
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
                            'code',
                            'name'
                        ])->with([
                            'category' => function ($query) {
                                $query->select([
                                    'id',
                                    'parent_id',
                                    'code',
                                    'name'
                                ])->with([
                                    'parent' => function ($query) {
                                        $query->select([
                                            'id',
                                            'parent_id',
                                            'code',
                                            'name'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'appearanceCases' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'appearance_id',
                            'unit_id',
                            'price'
                        ])->with([
                            'unit' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($vybeId, function ($query) use ($vybeId) {
                    $query->where('id', '=', $vybeId);
                })
                ->when($categoriesIds, function ($query) use ($categoriesIds) {
                    $query->whereHas('activity.category', function ($query) use ($categoriesIds) {
                        $query->whereNull('parent_id')
                            ->whereIn('id', $categoriesIds)
                            ->orWhereHas('parent', function ($query) use ($categoriesIds) {
                                $query->whereIn('id', $categoriesIds);
                            });
                    });
                })
                ->when($subcategoriesIds, function ($query) use ($subcategoriesIds) {
                    $query->whereHas('activity.category', function ($query) use ($subcategoriesIds) {
                        $query->whereNotNull('parent_id')
                            ->whereIn('id', $subcategoriesIds);
                    });
                })
                ->when($activitiesIds, function ($query) use ($activitiesIds) {
                    $query->whereHas('activity', function ($query) use ($activitiesIds) {
                        $query->whereIn('id', $activitiesIds);
                    });
                })
                ->when($typesIds, function ($query) use ($typesIds) {
                    $query->whereIn('type_id', $typesIds);
                })
                ->when($usersIds, function ($query) use ($usersIds) {
                    $query->whereHas('user', function ($query) use ($usersIds) {
                        $query->whereIn('id', $usersIds);
                    });
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->where('title', '=', $vybeTitle);
                })
                ->when($price, function ($query) use ($price) {
                    $query->whereHas('appearanceCases', function ($query) use ($price) {
                        $query->where('price', '=', $price);
                    });
                })
                ->when($unitsIds, function ($query) use ($unitsIds) {
                    $query->whereHas('appearanceCases', function ($query) use ($unitsIds) {
                        $query->whereHas('unit', function ($query) use ($unitsIds) {
                            $query->whereIn('id', $unitsIds);
                        });
                    });
                })
                ->when($statusesIds, function ($query) use ($statusesIds) {
                    $query->whereIn('status_id', $statusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'category') {
                        $query->orderBy(
                            Category::query()->select('categories.name')
                                ->join('activities', 'activities.category_id', '=', 'categories.id')
                                ->whereNull('categories.parent_id')
                                ->whereColumn('vybes.activity_id', 'activities.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'subcategory') {
                        $query->orderBy(
                            Category::query()->select('categories.name')
                                ->join('activities', 'activities.category_id', '=', 'categories.id')
                                ->whereNotNull('categories.parent_id')
                                ->whereColumn('vybes.activity_id', 'activities.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'activity') {
                        $query->orderBy(
                            Activity::query()->select('name')
                                ->whereColumn('vybes.activity_id', 'activities.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'type') {
                        $query->orderBy('type_id', $sortOrder);
                    }

                    if ($sortBy == 'user') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('vybes.user_id', 'users.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'title') {
                        $query->orderBy('title', $sortOrder);
                    }

//                    if ($sortBy == 'price') {
//                        $query->orderBy(
//                            AppearanceCase::select('price')
//                                ->whereColumn('vybes.id', 'appearance_cases.vybe_id')
//                                ->take(1),
//                            $sortOrder
//                        );
//                    }

//                    if ($sortBy == 'unit') {
//                        $query->orderBy(
//                            Unit::select('name')
//                                ->join('appearance_cases', 'appearance_cases.unit_id', '=', 'units.id')
//                                ->whereColumn('vybes.id', 'appearance_cases.vybe_id')
//                                ->take(1),
//                            $sortOrder
//                        );
//                    }

                    if ($sortBy == 'status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $vybeId
     * @param array|null $categoriesIds
     * @param array|null $subcategoriesIds
     * @param array|null $activitiesIds
     * @param array|null $typesIds
     * @param array|null $usersIds
     * @param string|null $vybeTitle
     * @param float|null $price
     * @param array|null $unitsIds
     * @param array|null $statusesIds
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
        ?int $vybeId = null,
        ?array $categoriesIds = null,
        ?array $subcategoriesIds = null,
        ?array $activitiesIds = null,
        ?array $typesIds = null,
        ?array $usersIds = null,
        ?string $vybeTitle = null,
        ?float $price = null,
        ?array $unitsIds = null,
        ?array $statusesIds = null,
        ?string $sortBy = null,
        ?string $sortOrder = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Vybe::query()
                ->select([
                    'id',
                    'user_id',
                    'activity_id',
                    'type_id',
                    'status_id',
                    'title'
                ])
                ->with([
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
                            'code',
                            'name'
                        ])->with([
                            'category' => function ($query) {
                                $query->select([
                                    'id',
                                    'parent_id',
                                    'code',
                                    'name'
                                ])->with([
                                    'parent' => function ($query) {
                                        $query->select([
                                            'id',
                                            'parent_id',
                                            'code',
                                            'name'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    },
                    'appearanceCases' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'appearance_id',
                            'unit_id',
                            'price'
                        ])->with([
                            'unit' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            }
                        ]);
                    }
                ])
                ->when($vybeId, function ($query) use ($vybeId) {
                    $query->where('id', '=', $vybeId);
                })
                ->when($categoriesIds, function ($query) use ($categoriesIds) {
                    $query->whereHas('activity.category', function ($query) use ($categoriesIds) {
                        $query->whereNull('parent_id')
                            ->whereIn('id', $categoriesIds)
                            ->orWhereHas('parent', function ($query) use ($categoriesIds) {
                                $query->whereIn('id', $categoriesIds);
                            });
                    });
                })
                ->when($subcategoriesIds, function ($query) use ($subcategoriesIds) {
                    $query->whereHas('activity.category', function ($query) use ($subcategoriesIds) {
                        $query->whereNotNull('parent_id')
                            ->whereIn('id', $subcategoriesIds);
                    });
                })
                ->when($activitiesIds, function ($query) use ($activitiesIds) {
                    $query->whereHas('activity', function ($query) use ($activitiesIds) {
                        $query->whereIn('id', $activitiesIds);
                    });
                })
                ->when($typesIds, function ($query) use ($typesIds) {
                    $query->whereIn('type_id', $typesIds);
                })
                ->when($usersIds, function ($query) use ($usersIds) {
                    $query->whereHas('user', function ($query) use ($usersIds) {
                        $query->whereIn('id', $usersIds);
                    });
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->where('title', '=', $vybeTitle);
                })
                ->when($price, function ($query) use ($price) {
                    $query->whereHas('appearanceCases', function ($query) use ($price) {
                        $query->where('price', '=', $price);
                    });
                })
                ->when($unitsIds, function ($query) use ($unitsIds) {
                    $query->whereHas('appearanceCases', function ($query) use ($unitsIds) {
                        $query->whereHas('unit', function ($query) use ($unitsIds) {
                            $query->whereIn('id', $unitsIds);
                        });
                    });
                })
                ->when($statusesIds, function ($query) use ($statusesIds) {
                    $query->whereIn('status_id', $statusesIds);
                })
                ->when($sortBy && $sortOrder, function ($query) use ($sortBy, $sortOrder) {
                    if ($sortBy == 'id') {
                        $query->orderBy('id', $sortOrder);
                    }

                    if ($sortBy == 'category') {
                        $query->orderBy(
                            Category::query()->select('categories.name')
                                ->join('activities', 'activities.category_id', '=', 'categories.id')
                                ->whereNull('categories.parent_id')
                                ->whereColumn('vybes.activity_id', 'activities.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'subcategory') {
                        $query->orderBy(
                            Category::query()->select('categories.name')
                                ->join('activities', 'activities.category_id', '=', 'categories.id')
                                ->whereNotNull('categories.parent_id')
                                ->whereColumn('vybes.activity_id', 'activities.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'activity') {
                        $query->orderBy(
                            Activity::query()->select('name')
                                ->whereColumn('vybes.activity_id', 'activities.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'type') {
                        $query->orderBy('type_id', $sortOrder);
                    }

                    if ($sortBy == 'user') {
                        $query->orderBy(
                            User::query()->select('username')
                                ->whereColumn('vybes.user_id', 'users.id'),
                            $sortOrder
                        );
                    }

                    if ($sortBy == 'title') {
                        $query->orderBy('title', $sortOrder);
                    }

//                    if ($sortBy == 'price') {
//                        $query->orderBy(
//                            AppearanceCase::select('price')
//                                ->whereColumn('vybes.id', 'appearance_cases.vybe_id')
//                                ->take(1),
//                            $sortOrder
//                        );
//                    }

//                    if ($sortBy == 'unit') {
//                        $query->orderBy(
//                            Unit::select('name')
//                                ->join('appearance_cases', 'appearance_cases.unit_id', '=', 'units.id')
//                                ->whereColumn('vybes.id', 'appearance_cases.vybe_id')
//                                ->take(1),
//                            $sortOrder
//                        );
//                    }

                    if ($sortBy == 'status') {
                        $query->orderBy('status_id', $sortOrder);
                    }
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $vybeId
     * @param array|null $categoriesIds
     * @param array|null $subcategoriesIds
     * @param array|null $activitiesIds
     * @param array|null $typesIds
     * @param array|null $usersIds
     * @param string|null $vybeTitle
     * @param float|null $price
     * @param array|null $unitsIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFilteredForAdminLabels(
        ?int $vybeId = null,
        ?array $categoriesIds = null,
        ?array $subcategoriesIds = null,
        ?array $activitiesIds = null,
        ?array $typesIds = null,
        ?array $usersIds = null,
        ?string $vybeTitle = null,
        ?float $price = null,
        ?array $unitsIds = null
    ) : Collection
    {
        try {
            return Vybe::query()
                ->select([
                    'id'
                ])
                ->when($vybeId, function ($query) use ($vybeId) {
                    $query->where('id', '=', $vybeId);
                })
                ->when($categoriesIds, function ($query) use ($categoriesIds) {
                    $query->whereHas('activity.category', function ($query) use ($categoriesIds) {
                        $query->whereNull('parent_id')
                            ->whereIn('id', $categoriesIds)
                            ->orWhereHas('parent', function ($query) use ($categoriesIds) {
                                $query->whereIn('id', $categoriesIds);
                            });
                    });
                })
                ->when($subcategoriesIds, function ($query) use ($subcategoriesIds) {
                    $query->whereHas('activity.category', function ($query) use ($subcategoriesIds) {
                        $query->whereNotNull('parent_id')
                            ->whereIn('id', $subcategoriesIds);
                    });
                })
                ->when($activitiesIds, function ($query) use ($activitiesIds) {
                    $query->whereHas('activity', function ($query) use ($activitiesIds) {
                        $query->whereIn('id', $activitiesIds);
                    });
                })
                ->when($typesIds, function ($query) use ($typesIds) {
                    $query->whereIn('type_id', $typesIds);
                })
                ->when($usersIds, function ($query) use ($usersIds) {
                    $query->whereHas('user', function ($query) use ($usersIds) {
                        $query->whereIn('id', $usersIds);
                    });
                })
                ->when($vybeTitle, function ($query) use ($vybeTitle) {
                    $query->where('title', '=', $vybeTitle);
                })
                ->when($price, function ($query) use ($price) {
                    $query->whereHas('appearanceCases', function ($query) use ($price) {
                        $query->where('price', '=', $price);
                    });
                })
                ->when($unitsIds, function ($query) use ($unitsIds) {
                    $query->whereHas('appearanceCases', function ($query) use ($unitsIds) {
                        $query->whereHas('unit', function ($query) use ($unitsIds) {
                            $query->whereIn('id', $unitsIds);
                        });
                    });
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User|null $user
     * @param array|null $unitsIds
     * @param int|null $appearanceId
     * @param int|null $genderId
     * @param string|null $cityPlaceId
     * @param Category|null $category
     * @param int|null $subcategoryId
     * @param array|null $personalityTraitsIds
     * @param int|null $activityId
     * @param array|null $typesIds
     * @param array|null $devicesIds
     * @param array|null $platformsIds
     * @param array|null $languagesIds
     * @param array|null $tagsIds
     * @param string|null $dateMin
     * @param string|null $dateMax
     * @param float|null $priceMin
     * @param float|null $priceMax
     * @param int|null $sort
     * @param bool|null $hasAllTags
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getWithFiltersForCatalog(
        ?User $user,
        ?array $unitsIds,
        ?int $appearanceId,
        ?int $genderId,
        ?string $cityPlaceId,
        ?Category $category,
        ?int $subcategoryId,
        ?array $personalityTraitsIds,
        ?int $activityId,
        ?array $typesIds,
        ?array $devicesIds,
        ?array $platformsIds,
        ?array $languagesIds,
        ?array $tagsIds,
        ?string $dateMin,
        ?string $dateMax,
        ?float $priceMin,
        ?float $priceMax,
        ?int $sort,
        ?bool $hasAllTags
    ) : Collection
    {
        try {
            return Vybe::query()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username',
                            'birth_date'
                        ])->with([
                            'personalityTraits' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'trait_id'
                                ]);
                            },
                            'languages' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'language_id'
                                ]);
                            },
                        ]);
                    },
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
                                    'name',
                                    'code'
                                ])->with([
                                    'categoryTags' => function ($query) {
                                        $query->select([
                                            'id',
                                            'category_id',
                                            'name',
                                            'code',
                                            'visible_in_category'
                                        ])->where('visible_in_category', '=', 1);
                                    },
                                    'subcategoryTags' => function ($query) {
                                        $query->select([
                                            'id',
                                            'subcategory_id',
                                            'name',
                                            'code',
                                            'visible_in_subcategory'
                                        ])->where('visible_in_subcategory', '=', 1);
                                    }
                                ]);
                            },
                            'tags' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name',
                                    'visible_in_category',
                                    'visible_in_subcategory'
                                ]);
                            },
                            'units' => function ($query) {
                                $query->select([
                                    'id',
                                    'type_id',
                                    'code',
                                    'name',
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
                            'city_place_id',
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
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    'orderItems' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'timeslot_id'
                        ])->with([
                            'timeslot' => function ($query) {
                                $query->withCount([
                                    'users'
                                ]);
                            }
                        ])->orderBy(
                            'created_at',
                            'desc'
                        )->limit(1);
                    }
                ])
                ->withCount([
                    'ratingVotes'
                ])
                ->when($user, function ($query) use ($user) {
                    $query->where('user_id', '=', $user->id);
                })
                ->when($unitsIds, function ($query) use ($unitsIds) {
                    $query->whereHas('activity', function ($query) use ($unitsIds) {
                        $query->whereHas('units', function ($query) use ($unitsIds) {
                            $query->whereIn('id', $unitsIds);
                        });
                    });
                })
                ->when($appearanceId, function ($query) use ($appearanceId) {
                    $query->whereHas('appearanceCases', function ($query) use ($appearanceId) {
                        $query->where('appearance_id', '=', $appearanceId);
                    });
                })
                ->when($genderId, function ($query) use ($genderId) {
                    $query->whereHas('user', function ($query) use ($genderId) {
                        $query->where('gender_id',  '=',  $genderId);
                    });
                })
                ->when($cityPlaceId, function ($query) use ($cityPlaceId) {
                    $query->whereHas('appearanceCases', function ($query) use ($cityPlaceId) {
                        $query->where('city_place_id', '=', $cityPlaceId);
                    });
                })
                ->when($category, function ($query) use ($category) {
                    $query->whereHas('activity', function ($query) use ($category) {
                        $query->where('category_id', '=', $category->id)
                            ->orWhereIn('category_id', $category->subcategories->pluck('id'));
                    });
                })
                ->when($subcategoryId, function ($query) use ($subcategoryId) {
                    $query->whereHas('activity', function ($query) use ($subcategoryId) {
                        $query->where('category_id', '=', $subcategoryId);
                    });
                })
                ->when($personalityTraitsIds, function ($query) use ($personalityTraitsIds) {
                    $query->whereHas('user', function ($query) use ($personalityTraitsIds) {
                        $query->whereHas('personalityTraits', function ($query) use ($personalityTraitsIds) {
                            $query->whereIn('trait_id', $personalityTraitsIds);
                        });
                    });
                })
                ->when($activityId, function ($query) use ($activityId) {
                    $query->where('activity_id', '=', $activityId);
                })
                ->when($typesIds, function ($query) use ($typesIds) {
                    $query->whereIn('type_id', $typesIds);
                })
                ->when($devicesIds, function ($query) use ($devicesIds) {
                    $query->whereHas('devices', function ($query) use ($devicesIds) {
                        $query->whereIn('device_id', $devicesIds);
                    });
                })
                ->when($platformsIds, function ($query) use ($platformsIds) {
                    $query->whereHas('appearanceCases', function ($query) use ($platformsIds) {
                        $query->whereHas('platforms', function ($query) use ($platformsIds) {
                            $query->whereIn('platform_id', $platformsIds);
                        });
                    });
                })
                ->when($languagesIds, function ($query) use ($languagesIds) {
                    $query->whereHas('user', function ($query) use ($languagesIds) {
                        $query->whereHas('languages', function ($query) use ($languagesIds) {
                            $query->whereIn('language_id', $languagesIds);
                        });
                    });
                })
                ->when($tagsIds, function ($query) use ($tagsIds, $hasAllTags) {
                    $query->whereHas('activity', function ($query) use ($tagsIds, $hasAllTags) {
                        if (!$hasAllTags) {
                            $query->whereHas('tags', function ($query) use ($tagsIds) {
                                $query->whereIn('tag_id', $tagsIds);
                            });
                        }
                    });
                })
                ->when($dateMin, function ($query) use ($dateMin) {
                    $query->whereHas('user', function ($query) use ($dateMin) {
                        $query->where('birth_date', '>=', $dateMin);
                    });
                })
                ->when($dateMax, function ($query) use ($dateMax) {
                    $query->whereHas('user', function ($query) use ($dateMax) {
                        $query->where('birth_date', '<=', $dateMax);
                    });
                })
                ->when($priceMin, function ($query) use ($priceMin) {
                    $query->whereHas('appearanceCases', function ($query) use ($priceMin) {
                        $query->where('price', '>=', $priceMin);
                    });
                })
                ->when($priceMax, function ($query) use ($priceMax) {
                    $query->whereHas('appearanceCases', function ($query) use ($priceMax) {
                        $query->where('price', '<=', $priceMax);
                    });
                })
                ->when($sort, function ($query) use ($sort) {
                    if ($sort == 2) {
                        $query->orderBy('rating');
                    } elseif ($sort == 3) {
                        $query->orderBy('created_at');
                    } elseif ($sort == 4) {
                        $query->inRandomOrder();
                    }
                })
                ->where('status_id', '=', VybeStatusList::getPublishedItem()->id)
                ->where('access_id', '!=', VybeAccessList::getPrivate()->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param array|null $typesIds
     * @param array|null $statusesIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getWithFiltersForDashboard(
        User $user,
        ?array $typesIds = null,
        ?array $statusesIds = null
    ) : Collection
    {
        try {
            return Vybe::query()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username',
                            'avatar_id',
                            'voice_sample_id'
                        ]);
                    },
                    'activity' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'appearanceCases' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'appearance_id',
                            'unit_id',
                            'city_place_id',
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
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    'devices' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'orderItems' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'timeslot_id'
                        ])->with([
                            'timeslot' => function ($query) {
                                $query->withCount([
                                    'users'
                                ]);
                            }
                        ])->orderBy(
                            'created_at',
                            'desc'
                        )->limit(1);
                    },
                    'publishRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    },
                    'changeRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    },
                    'unpublishRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    },
                    'unsuspendRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    },
                    'deletionRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    }
                ])
                ->withCount([
                    'ratingVotes'
                ])
                ->when($typesIds, function ($query) use ($typesIds) {
                    $query->whereIn('type_id', $typesIds);
                })
                ->when($statusesIds, function ($query) use ($statusesIds) {
                    $query->whereIn('status_id', $statusesIds);
                })
                ->where('step_id', '=', VybeStepList::getCompleted()->id)
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
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
    public function getUncompletedForDashboard(
        User $user
    ) : Collection
    {
        try {
            return Vybe::query()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username',
                            'avatar_id',
                            'voice_sample_id'
                        ]);
                    },
                    'activity' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'appearanceCases' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'appearance_id',
                            'unit_id',
                            'city_place_id',
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
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    'devices' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'orderItems' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'timeslot_id'
                        ])->with([
                            'timeslot' => function ($query) {
                                $query->withCount([
                                    'users'
                                ]);
                            }
                        ])->orderBy(
                            'created_at',
                            'desc'
                        )->limit(1);
                    },
                    'publishRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    },
                    'changeRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    },
                    'unpublishRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    },
                    'unsuspendRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    },
                    'deletionRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    }
                ])
                ->withCount([
                    'ratingVotes'
                ])
                ->where('step_id', '!=', VybeStepList::getCompleted()->id)
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param array|null $typesIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFavoritesWithFiltersForDashboard(
        User $user,
        ?array $typesIds = null
    ) : Collection
    {
        try {
            return Vybe::query()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username',
                            'avatar_id',
                            'voice_sample_id'
                        ]);
                    },
                    'activity' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'appearanceCases' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'appearance_id',
                            'unit_id',
                            'city_place_id',
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
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    'devices' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'code'
                        ]);
                    },
                    'orderItems' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'timeslot_id'
                        ])->with([
                            'timeslot' => function ($query) {
                                $query->withCount([
                                    'users'
                                ]);
                            }
                        ])->orderBy(
                            'created_at',
                            'desc'
                        )->limit(1);
                    },
                    'publishRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    },
                    'changeRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    },
                    'unpublishRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    },
                    'unsuspendRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    },
                    'deletionRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'request_status_id',
                            'created_at'
                        ]);
                    }
                ])
                ->withCount([
                    'ratingVotes'
                ])
                ->when($typesIds, function ($query) use ($typesIds) {
                    $query->whereIn('type_id', $typesIds);
                })
                ->whereHas('user', function($query) use ($user) {
                    $query->where('account_status_id', '=', AccountStatusList::getActive()->id);
                })
                ->whereHas('userFavorites', function($query) use ($user) {
                    $query->where('favorite_vybes.user_id', '=', $user->id);
                })
                ->whereIn('status_id', [
                    VybeStatusList::getPublishedItem()->id,
                    VybeStatusList::getPausedItem()->id
                ])
                ->where('step_id', '=', VybeStepList::getCompleted()->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
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
            return Vybe::query()
                ->with([
                    'appearanceCases.unit',
                    'activity' => function ($query) {
                        $query->select([
                            'id',
                            'category_id',
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
                    },
                    'schedules' => function ($query) {
                        $query->select([
                            'id',
                            'vybe_id',
                            'datetime_from',
                            'datetime_to'
                        ]);
                    }
                ])

                ->withCount([
                    'ratingVotes'
                ])
                ->whereRaw('lower(title) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->orderBy('id', 'desc')
                ->when($limit, function ($query) use ($limit) {
                    $query->limit($limit);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
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
    public function getByUser(
        User $user
    ) : Collection
    {
        try {
            return $user->vybes()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username'
                        ]);
                    },
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
                                    'name',
                                    'code'
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
                                    'type_id',
                                    'name',
                                    'code',
                                    'duration',
                                    'visible'
                                ]);
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    }
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $activityId
     * @param int|null $vybeSortId
     * @param array|null $vybeTypesIds
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getByUserPaginated(
        User $user,
        ?int $activityId = null,
        ?int $vybeSortId = null,
        ?array $vybeTypesIds = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return $user->vybes()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username'
                        ]);
                    },
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
                                    'name',
                                    'code'
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
                                    'type_id',
                                    'name',
                                    'code',
                                    'duration',
                                    'visible'
                                ]);
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    }
                ])
                ->when($activityId, function ($query) use ($activityId) {
                    $query->where('activity_id', '=', $activityId);
                })
                ->when($vybeSortId, function ($query) use ($vybeSortId) {
                    $vybeSortListItem = VybeSortList::getItem(
                        $vybeSortId
                    );

                    if ($vybeSortListItem->isTopRated()) {
                        $query->orderBy('rating', 'DESC');
                    } elseif ($vybeSortListItem->isDate()) {
                        $query->orderBy('created_at', 'DESC');
                    }
                })
                ->when($vybeTypesIds, function ($query) use ($vybeTypesIds) {
                    $query->whereIn('type_id', $vybeTypesIds);
                })
                ->whereIn('status_id', [
                    VybeStatusList::getPublishedItem()->id,
                    VybeStatusList::getPausedItem()->id
                ])
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
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
    public function getForAdminByUser(
        User $user
    ) : Collection
    {
        try {
            return Vybe::query()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username'
                        ]);
                    },
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
                        ]);
                    }
                ])
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getForAdminByUserPaginated(
        User $user,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return Vybe::query()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username'
                        ]);
                    },
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
                        ]);
                    }
                ])
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
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
    public function getFavoritesByUser(
        User $user
    ) : Collection
    {
        try {
            return $user->favoriteVybes()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username',
                            'birth_date'
                        ])->with([
                            'personalityTraits' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'trait_id'
                                ]);
                            },
                            'languages' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'language_id'
                                ]);
                            },
                        ]);
                    },
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
                                    'name',
                                    'code'
                                ])->with([
                                    'categoryTags' => function ($query) {
                                        $query->select([
                                            'id',
                                            'category_id',
                                            'name',
                                            'code',
                                            'visible_in_category'
                                        ])->where('visible_in_category', '=', 1);
                                    },
                                    'subcategoryTags' => function ($query) {
                                        $query->select([
                                            'id',
                                            'subcategory_id',
                                            'name',
                                            'code',
                                            'visible_in_subcategory'
                                        ])->where('visible_in_subcategory', '=', 1);
                                    }
                                ]);
                            },
                            'tags' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name',
                                    'visible_in_category',
                                    'visible_in_subcategory'
                                ]);
                            },
                            'units' => function ($query) {
                                $query->select([
                                    'id',
                                    'type_id',
                                    'code',
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
                            'city_place_id',
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
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    }
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getFavoritesByUserPaginated(
        User $user,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return $user->favoriteVybes()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username',
                            'birth_date'
                        ])->with([
                            'personalityTraits' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'trait_id'
                                ]);
                            },
                            'languages' => function ($query) {
                                $query->select([
                                    'id',
                                    'user_id',
                                    'language_id'
                                ]);
                            },
                        ]);
                    },
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
                                    'name',
                                    'code'
                                ])->with([
                                    'categoryTags' => function ($query) {
                                        $query->select([
                                            'id',
                                            'category_id',
                                            'name',
                                            'code',
                                            'visible_in_category'
                                        ])->where('visible_in_category', '=', 1);
                                    },
                                    'subcategoryTags' => function ($query) {
                                        $query->select([
                                            'id',
                                            'subcategory_id',
                                            'name',
                                            'code',
                                            'visible_in_subcategory'
                                        ])->where('visible_in_subcategory', '=', 1);
                                    }
                                ]);
                            },
                            'tags' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name',
                                    'visible_in_category',
                                    'visible_in_subcategory'
                                ]);
                            },
                            'units' => function ($query) {
                                $query->select([
                                    'id',
                                    'type_id',
                                    'code',
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
                            'city_place_id',
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
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    }
                ])
                ->whereIn('status_id', [
                    VybeStatusList::getPublishedItem()->id,
                    VybeStatusList::getPausedItem()->id
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $search
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFavoritesWithSearchByUser(
        User $user,
        string $search
    ) : Collection
    {
        try {
            return $user->favoriteVybes()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username'
                        ]);
                    },
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
                                    'name',
                                    'code'
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
                                    'type_id',
                                    'name',
                                    'code',
                                    'duration',
                                    'visible'
                                ]);
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    }
                ])
                ->whereRaw('lower(title) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $search
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getFavoritesWithSearchByUserPaginated(
        User $user,
        string $search,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return $user->favoriteVybes()
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'gender_id',
                            'username'
                        ]);
                    },
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
                                    'name',
                                    'code'
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
                                    'type_id',
                                    'name',
                                    'code',
                                    'duration'
                                ]);
                            },
                            'platforms' => function ($query) {
                                $query->select([
                                    'id',
                                    'name',
                                    'code'
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
                    }
                ])
                ->whereRaw('lower(title) like (?)', ['%' . strtolower(trim($search)) . '%'])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $vybesIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $vybesIds
    ) : Collection
    {
        try {
            return Vybe::query()
                ->whereIn('id', $vybesIds)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return array
     *
     * @throws DatabaseException
     */
    public function getFavoritesIdsByUser(
        User $user
    ) : array
    {
        try {
            return $user->favoriteVybes()
                ->pluck('id')
                ->toArray();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Vybe|null
     *
     * @throws DatabaseException
     */
    public function getLastVybe() : ?Vybe
    {
        try {
            return Vybe::query()
                ->orderBy('id', 'desc')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function isUserFavorite(
        Vybe $vybe,
        User $user
    ) : bool
    {
        try {
            return $user->favoriteVybes()
                ->where('vybe_id', '=', $vybe->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function belongsToUser(
        Vybe $vybe,
        User $user
    ) : bool
    {
        try {
            return $user->vybes()
                ->where('id', '=', $vybe->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $vybesIds
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function getTypesByIdsCount(
        array $vybesIds
    ) : Vybe
    {
        try {
            return Vybe::query()
                ->whereIn('id', $vybesIds)
                ->selectRaw('sum(case when type_id = 1 then 1 else 0 end) as solo')
                ->selectRaw('sum(case when type_id = 2 then 1 else 0 end) as grouped')
                ->selectRaw('sum(case when type_id = 3 then 1 else 0 end) as event')
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getOrderedByUserVybesPaginated(
        User $user,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Vybe::query()
                ->select([
                    'id',
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
                ])
                ->with([
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
                ])
                ->where('status_id', '=', VybeStatusList::getPublishedItem()->id)
                ->whereHas('orderItems.order', function ($query) use ($user) {
                    $query->where('buyer_id', '=', $user->id);
                })
                ->paginate(config('repositories.vybe.indexPerPage'), ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $users
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getVybesByUsersPaginated(
        Collection $users,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Vybe::query()
                ->select([
                    'id',
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
                ])
                ->with([
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
                ])
                ->where('status_id', '=', VybeStatusList::getPublishedItem()->id)
                ->whereIn('user_id', $users->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->paginate(config('repositories.vybe.indexPerPage'), ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param integer|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getVybesNotDiscoveredPaginated(
        User $user,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Vybe::query()
                ->select([
                    'id',
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
                ])
                ->with([
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
                ])
                ->where('status_id', '=', VybeStatusList::getPublishedItem()->id)
                ->where('user_id', '!=', $user->id)
                ->whereNotIn('id', $user->favoriteVybes
                    ->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->has('orderItems', '=', 0)
                ->orWhereHas('orderItems.order', function ($query) use ($user) {
                    $query->where('buyer_id', '!=', $user->id);
                })
                ->paginate(config('repositories.vybe.indexPerPage'), ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getVybesRecommendedForUserPaginated(
        User $user,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Vybe::query()
                ->select([
                    'id',
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
                ])
                ->with([
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
                ])
                ->where('status_id', '=', VybeStatusList::getPublishedItem()->id)
                ->where('user_id', '!=', $user->id)
                ->whereNotIn('id', $user->favoriteVybes
                    ->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->whereIn('activity_id', $user->favoriteVybes
                    ->pluck('activity_id')
                    ->values()
                    ->toArray()
                )
                ->has('orderItems', '=', 0)
                ->orWhereHas('orderItems.order', function ($query) use ($user) {
                    $query->where('buyer_id', '!=', $user->id);
                })
                ->paginate(config('repositories.vybe.indexRecommendedPerPage'), ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Device $device
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getByDevicePaginated(
        Device $device,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return Vybe::query()
                ->with([
                    'appearanceCases'
                ])
                ->whereHas('devices', function ($query) use ($device) {
                    $query->where('device_id', $device->id);
                })
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getByPlatformPaginated(
        Platform $platform,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return Vybe::query()
                ->with([
                    'appearanceCases'
                ])
                ->whereHas('platforms', function ($query) use ($platform) {
                    $query->where('platform_id', $platform->id);
                })
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Unit $unit
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getByUnitPaginated(
        Unit $unit,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return Vybe::query()
                ->with([
                    'appearanceCases'
                ])
                ->whereHas('appearanceCases.unit', function ($query) use ($unit) {
                    $query->where('id', $unit->id);
                })
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param Activity|null $activity
     * @param VybeTypeListItem|null $vybeTypeListItem
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param VybeAccessListItem|null $vybeAccessListItem
     * @param string|null $vybeAccessPassword
     * @param VybeShowcaseListItem|null $vybeShowcaseListItem
     * @param VybeStatusListItem|null $vybeStatusListItem
     * @param VybeAgeLimitListItem|null $vybeAgeLimitListItem
     * @param VybeOrderAcceptListItem|null $vybeOrderAcceptListItem
     * @param string|null $title
     * @param int|null $userCount
     * @param int|null $orderAdvance
     * @param array|null $imagesIds
     * @param array|null $videosIds
     *
     * @return Vybe|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        ?Activity $activity,
        ?VybeTypeListItem $vybeTypeListItem,
        ?VybePeriodListItem $vybePeriodListItem,
        ?VybeAccessListItem $vybeAccessListItem,
        ?string $vybeAccessPassword,
        ?VybeShowcaseListItem $vybeShowcaseListItem,
        ?VybeStatusListItem $vybeStatusListItem,
        ?VybeAgeLimitListItem $vybeAgeLimitListItem,
        ?VybeOrderAcceptListItem $vybeOrderAcceptListItem,
        ?string $title,
        ?int $userCount,
        ?int $orderAdvance,
        ?array $imagesIds = null,
        ?array $videosIds = null
    ) : ?Vybe
    {
        try {
            return Vybe::query()->create([
                'user_id'         => $user->id,
                'activity_id'     => $activity?->id,
                'type_id'         => $vybeTypeListItem?->id,
                'period_id'       => $vybePeriodListItem?->id,
                'access_id'       => $vybeAccessListItem?->id,
                'access_password' => $vybeAccessPassword ? Crypt::encrypt($vybeAccessPassword) : null,
                'showcase_id'     => $vybeShowcaseListItem?->id,
                'status_id'       => $vybeStatusListItem?->id,
                'age_limit_id'    => $vybeAgeLimitListItem?->id,
                'order_accept_id' => $vybeOrderAcceptListItem?->id,
                'title'           => $title ? trim($title) : null,
                'user_count'      => $userCount,
                'order_advance'   => $orderAdvance,
                'images_ids'      => $imagesIds,
                'videos_ids'      => $videosIds
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param Activity|null $activity
     * @param VybeTypeListItem|null $vybeTypeListItem
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param VybeAccessListItem|null $vybeAccessListItem
     * @param VybeShowcaseListItem|null $vybeShowcaseListItem
     * @param VybeStatusListItem|null $vybeStatusListItem
     * @param VybeAgeLimitListItem|null $vybeAgeLimitListItem
     * @param VybeOrderAcceptListItem|null $vybeOrderAcceptListItem
     * @param string|null $title
     * @param int|null $userCount
     * @param int|null $orderAdvance
     * @param array|null $imagesIds
     * @param array|null $videosIds
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function update(
        Vybe $vybe,
        ?Activity $activity,
        ?VybeTypeListItem $vybeTypeListItem,
        ?VybePeriodListItem $vybePeriodListItem,
        ?VybeAccessListItem $vybeAccessListItem,
        ?VybeShowcaseListItem $vybeShowcaseListItem,
        ?VybeStatusListItem $vybeStatusListItem,
        ?VybeAgeLimitListItem $vybeAgeLimitListItem,
        ?VybeOrderAcceptListItem $vybeOrderAcceptListItem,
        ?string $title,
        ?int $userCount,
        ?int $orderAdvance,
        ?array $imagesIds = null,
        ?array $videosIds = null
    ) : Vybe
    {
        try {
            $vybe->update([
                'activity_id'     => $activity ? $activity->id : $vybe->activity_id,
                'type_id'         => $vybeTypeListItem ? $vybeTypeListItem->id : $vybe->type_id,
                'period_id'       => $vybePeriodListItem ? $vybePeriodListItem->id : $vybe->period_id,
                'access_id'       => $vybeAccessListItem ? $vybeAccessListItem->id : $vybe->access_id,
                'showcase_id'     => $vybeShowcaseListItem ? $vybeShowcaseListItem->id : $vybe->showcase_id,
                'status_id'       => $vybeStatusListItem ? $vybeStatusListItem->id : $vybe->status_id,
                'age_limit_id'    => $vybeAgeLimitListItem ? $vybeAgeLimitListItem->id : $vybe->age_limit_id,
                'order_accept_id' => $vybeOrderAcceptListItem ? $vybeOrderAcceptListItem->id : $vybe->order_accept_id,
                'title'           => $title ? trim($title) : $vybe->title,
                'user_count'      => $userCount ?: $vybe->user_count,
                'order_advance'   => $orderAdvance ?: $vybe->order_advance,
                'images_ids'      => $imagesIds ?: $vybe->images_ids,
                'videos_ids'      => $videosIds ?: $vybe->videos_ids
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param Activity|null $activity
     * @param VybeTypeListItem|null $vybeTypeListItem
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param string|null $title
     * @param int|null $userCount
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateFirstStep(
        Vybe $vybe,
        ?Activity $activity,
        ?VybeTypeListItem $vybeTypeListItem,
        ?VybePeriodListItem $vybePeriodListItem,
        ?string $title,
        ?int $userCount
    ) : Vybe
    {
        try {
            $vybe->update([
                'activity_id' => $activity?->id,
                'type_id'     => $vybeTypeListItem?->id,
                'period_id'   => $vybePeriodListItem?->id,
                'title'       => $title ? trim($title) : null,
                'user_count'  => $userCount ?: null
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param VybeAccessListItem|null $vybeAccessListItem
     * @param VybeShowcaseListItem|null $vybeShowcaseListItem
     * @param VybeStatusListItem|null $vybeStatusListItem
     * @param VybeOrderAcceptListItem|null $vybeOrderAcceptListItem
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateFifthStep(
        Vybe $vybe,
        ?VybeAccessListItem $vybeAccessListItem,
        ?VybeShowcaseListItem $vybeShowcaseListItem,
        ?VybeStatusListItem $vybeStatusListItem,
        ?VybeOrderAcceptListItem $vybeOrderAcceptListItem = null
    ) : Vybe
    {
        try {
            $vybe->update([
                'access_id'       => $vybeAccessListItem?->id,
                'showcase_id'     => $vybeShowcaseListItem?->id,
                'status_id'       => $vybeStatusListItem?->id,
                'order_accept_id' => $vybeOrderAcceptListItem?->id
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param Activity $activity
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateActivity(
        Vybe $vybe,
        Activity $activity
    ) : Vybe
    {
        try {
            $vybe->update([
                'activity_id' => $activity->id
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param VybeStatusListItem $vybeStatusListItem
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        Vybe $vybe,
        VybeStatusListItem $vybeStatusListItem
    ) : Vybe
    {
        try {
            $vybe->update([
                'status_id' => $vybeStatusListItem->id
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param array|null $imagesIds
     * @param array|null $videosIds
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateMediaIds(
        Vybe $vybe,
        ?array $imagesIds,
        ?array $videosIds
    ) : Vybe
    {
        try {
            $vybe->update([
                'images_ids' => !is_null($imagesIds) ? $imagesIds : $vybe->images_ids,
                'videos_ids' => !is_null($videosIds) ? $videosIds : $vybe->videos_ids
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param VybeTypeListItem $vybeTypeListItem
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateType(
        Vybe $vybe,
        VybeTypeListItem $vybeTypeListItem
    ) : Vybe
    {
        try {
            $vybe->update([
                'type_id' => $vybeTypeListItem->id
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param VybeStepListItem $vybeStepListItem
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateStep(
        Vybe $vybe,
        VybeStepListItem $vybeStepListItem
    ) : Vybe
    {
        try {
            $vybe->update([
                'step_id' => $vybeStepListItem->id
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param int|null $orderAdvance
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateOrderAdvance(
        Vybe $vybe,
        ?int $orderAdvance
    ) : Vybe
    {
        try {
            $vybe->update([
                'order_advance' => $orderAdvance ?: $vybe->order_advance
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string|null $accessPassword
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateAccessPassword(
        Vybe $vybe,
        ?string $accessPassword
    ) : Vybe
    {
        try {
            $vybe->update([
                'access_password' => $accessPassword ?
                    Crypt::encrypt($accessPassword) :
                    $vybe->access_password
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param VybeAgeLimitListItem $vybeAgeLimitListItem
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateAgeLimit(
        Vybe $vybe,
        VybeAgeLimitListItem $vybeAgeLimitListItem
    ) : Vybe
    {
        try {
            $vybe->update([
                'age_limit_id' => $vybeAgeLimitListItem->id
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     */
    public function updateRating(
        Vybe $vybe
    ) : void
    {
        $totalRatings = 0;

        /** @var VybeRatingVote $vybeRatingVote */
        foreach ($vybe->ratingVotes as $vybeRatingVote) {
            $totalRatings = $totalRatings + $vybeRatingVote->rating;
        }

        $vybe->rating = round(
            $totalRatings / count($vybe->ratingVotes),
            1
        );

        $vybe->rating_votes++;
        $vybe->save();
    }

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param string|null $suspendReason
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function updateSuspendReason(
        Vybe $vybe,
        ?string $suspendReason
    ) : Vybe
    {
        try {
            $vybe->update([
                'suspend_reason' => $suspendReason
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return Vybe
     *
     * @throws DatabaseException
     */
    public function increaseVersion(
        Vybe $vybe
    ) : Vybe
    {
        try {
            $vybe->update([
                'version' => $vybe->version + 1
            ]);

            return $vybe;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param Device $device
     *
     * @throws DatabaseException
     */
    public function attachDevice(
        Vybe $vybe,
        Device $device
    ) : void
    {
        try {
            $vybe->devices()->sync([
                $device->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param array $devicesIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachDevices(
        Vybe $vybe,
        array $devicesIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $vybe->devices()->sync(
                $devicesIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param Device $device
     *
     * @throws DatabaseException
     */
    public function detachDevice(
        Vybe $vybe,
        Device $device
    ) : void
    {
        try {
            $vybe->devices()->detach([
                $device->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param array $devicesIds
     *
     * @throws DatabaseException
     */
    public function detachDevices(
        Vybe $vybe,
        array $devicesIds
    ) : void
    {
        try {
            $vybe->devices()->detach(
                $devicesIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Vybe $vybe
    ) : bool
    {
        try {
            return $vybe->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function forceDelete(
        Vybe $vybe
    ) : bool
    {
        try {
            return $vybe->forceDelete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybe.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
