<?php

namespace App\Repositories\Suggestion;

use App\Exceptions\DatabaseException;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Models\MongoDb\Suggestion\DeviceSuggestion;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Device;
use App\Repositories\BaseRepository;
use App\Repositories\Suggestion\Interfaces\DeviceSuggestionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Class DeviceSuggestionRepository
 *
 * @package App\Repositories\Suggestion
 */
class DeviceSuggestionRepository extends BaseRepository implements DeviceSuggestionRepositoryInterface
{
    /**
     * DeviceSuggestionRepository constructor
     */
    public function __construct()
    {
        $this->cacheTime = config('repositories.deviceSuggestion.cacheTime');
        $this->perPage = config('repositories.deviceSuggestion.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return DeviceSuggestion|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?DeviceSuggestion
    {
        try {
            return DeviceSuggestion::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return DeviceSuggestion|null
     *
     * @throws DatabaseException
     */
    public function findForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : ?DeviceSuggestion
    {
        try {
            return DeviceSuggestion::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return DeviceSuggestion|null
     *
     * @throws DatabaseException
     */
    public function findForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : ?DeviceSuggestion
    {
        try {
            return DeviceSuggestion::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
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
            return Cache::remember('deviceSuggestionRequests.all.count', $this->cacheTime,
                function () {
                    return DeviceSuggestion::query()
                        ->where('status_id', '=', RequestStatusList::getPendingItem()->id)
                        ->count();
                }
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
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
     * @param string|null $deviceName
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
        ?string $deviceName
    ) : Collection
    {
        try {
            return DeviceSuggestion::query()
                ->with([
                    'vybePublishRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'activity_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'title',
                                    'user_id',
                                    'version',
                                    'period_id'
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
                            },
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            },
                            'csauSuggestions'
                        ]);
                    },
                    'vybeChangeRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'activity_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'title',
                                    'user_id',
                                    'version',
                                    'period_id'
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
                            },
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            },
                            'csauSuggestions'
                        ]);
                    },
                    'device' => function ($query) {
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
                    $query->whereHas('vybePublishRequest.vybe',
                        function ($query) use ($vybeTitle) {
                            $query->where('title', 'LIKE', '%' . $vybeTitle . '%');
                        }
                    )->orWhereHas('vybeChangeRequest.vybe',
                        function ($query) use ($vybeTitle) {
                            $query->where('title', 'LIKE', '%' . $vybeTitle . '%');
                        }
                    );
                })
                ->when($deviceName, function ($query) use ($deviceName) {
                    $query->where('suggestion', 'LIKE', '%' . $deviceName . '%');
                })
                ->where('status_id', '=', RequestStatusList::getPendingItem()->id)
                ->orderBy('_id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
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
     * @param string|null $deviceName
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
        ?string $deviceName,
        ?int $page = 1,
        ?int $perPage = 18
    ) : LengthAwarePaginator
    {
        try {
            return DeviceSuggestion::query()
                ->with([
                    'vybePublishRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'activity_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'title',
                                    'user_id',
                                    'version',
                                    'period_id'
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
                            },
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            },
                            'csauSuggestions'
                        ]);
                    },
                    'vybeChangeRequest' => function ($query) {
                        $query->select([
                            '_id',
                            'vybe_id',
                            'activity_id'
                        ])->with([
                            'vybe' => function ($query) {
                                $query->select([
                                    'id',
                                    'title',
                                    'user_id',
                                    'version',
                                    'period_id'
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
                            },
                            'activity' => function ($query) {
                                $query->select([
                                    'id',
                                    'code',
                                    'name'
                                ]);
                            },
                            'csauSuggestions'
                        ]);
                    },
                    'device' => function ($query) {
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
                    $query->whereHas('vybePublishRequest.vybe',
                        function ($query) use ($vybeTitle) {
                            $query->where('title', 'LIKE', '%' . $vybeTitle . '%');
                        }
                    )->orWhereHas('vybeChangeRequest.vybe',
                        function ($query) use ($vybeTitle) {
                            $query->where('title', 'LIKE', '%' . $vybeTitle . '%');
                        }
                    );
                })
                ->when($deviceName, function ($query) use ($deviceName) {
                    $query->where('suggestion', 'LIKE', '%' . $deviceName . '%');
                })
                ->where('status_id', '=', RequestStatusList::getPendingItem()->id)
                ->orderBy('_id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest|null $vybePublishRequest
     * @param VybeChangeRequest|null $vybeChangeRequest
     * @param Device|null $device
     * @param string|null $suggestion
     * @param bool|null $visible
     *
     * @return DeviceSuggestion|null
     *
     * @throws DatabaseException
     */
    public function store(
        ?VybePublishRequest $vybePublishRequest,
        ?VybeChangeRequest $vybeChangeRequest,
        ?Device $device,
        ?string $suggestion,
        ?bool $visible = true
    ) : ?DeviceSuggestion
    {
        try {
            return DeviceSuggestion::query()->create([
                'vybe_publish_request_id' => $vybePublishRequest?->_id,
                'vybe_change_request_id'  => $vybeChangeRequest?->_id,
                'device_id'               => $device?->id,
                'suggestion'              => $suggestion,
                'visible'                 => $visible,
                'status_id'               => $device ?
                    RequestStatusList::getAcceptedItem()->id :
                    RequestStatusList::getPendingItem()->id,
                'admin_id'                => null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param DeviceSuggestion $deviceSuggestion
     * @param Device|null $device
     * @param string|null $suggestion
     * @param bool|null $visible
     *
     * @return DeviceSuggestion
     *
     * @throws DatabaseException
     */
    public function update(
        DeviceSuggestion $deviceSuggestion,
        ?Device $device,
        ?string $suggestion,
        ?bool $visible
    ) : DeviceSuggestion
    {
        try {
            $deviceSuggestion->update([
                'device_id'  => $device ? $device->id : $deviceSuggestion->device_id,
                'suggestion' => $suggestion ? trim($suggestion) : $deviceSuggestion->suggestion,
                'visible'    => !is_null($visible) ? $visible : $deviceSuggestion->visible
            ]);

            return $deviceSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param DeviceSuggestion $deviceSuggestion
     * @param RequestStatusListItem $requestStatusListItem
     *
     * @return DeviceSuggestion
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        DeviceSuggestion $deviceSuggestion,
        RequestStatusListItem $requestStatusListItem
    ) : DeviceSuggestion
    {
        try {
            $deviceSuggestion->update([
                'status_id' => $requestStatusListItem->id
            ]);

            return $deviceSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param DeviceSuggestion $deviceSuggestion
     * @param Admin $admin
     *
     * @return DeviceSuggestion
     *
     * @throws DatabaseException
     */
    public function updateAdmin(
        DeviceSuggestion $deviceSuggestion,
        Admin $admin
    ) : DeviceSuggestion
    {
        try {
            $deviceSuggestion->update([
                'admin_id' => $admin->id
            ]);

            return $deviceSuggestion;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        DeviceSuggestion $deviceSuggestion
    ) : bool
    {
        try {
            return $deviceSuggestion->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
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
            return DeviceSuggestion::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->where('status_id', '=', RequestStatusList::getPendingItem()->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
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
            return DeviceSuggestion::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->where('status_id', '=', RequestStatusList::getPendingItem()->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/suggestion/deviceSuggestion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
