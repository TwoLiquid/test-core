<?php

namespace App\Repositories\IpRegistrationList;

use App\Exceptions\DatabaseException;
use App\Models\MySql\IpBanList;
use App\Models\MySql\IpRegistrationList;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\IpRegistrationList\Interfaces\IpRegistrationListRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class IpRegistrationListRepository
 *
 * @package App\Repositories\IpRegistrationList
 */
class IpRegistrationListRepository extends BaseRepository implements IpRegistrationListRepositoryInterface
{
    /**
     * IpRegistrationListRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.ipRegistrationList.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return IpRegistrationList|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?IpRegistrationList
    {
        try {
            return IpRegistrationList::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipRegistrationList.' . __FUNCTION__),
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
            return IpRegistrationList::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipRegistrationList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return IpBanList::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipRegistrationList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $registrationDateFrom
     * @param string|null $registrationDateTo
     * @param string|null $ipAddress
     * @param string|null $username
     * @param string|null $name
     * @param array|null $statusesIds
     * @param string|null $location
     * @param bool|null $vpn
     * @param string|null $duplicates
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllFiltered(
        ?string $registrationDateFrom = null,
        ?string $registrationDateTo = null,
        ?string $ipAddress = null,
        ?string $username = null,
        ?string $name = null,
        ?array $statusesIds = null,
        ?string $location = null,
        ?bool $vpn = null,
        ?string $duplicates = null
    ) : Collection
    {
        try {
            return IpRegistrationList::query()
                ->select([
                    'id',
                    'user_id',
                    'ip_address',
                    'vpn'
                ])
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'account_status_id',
                            'current_city_place_id',
                            'username',
                            'signed_up_at'
                        ])->with([
                            'currentCityPlace.countryPlace'
                        ]);
                    },
                    'duplicates' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'ip_address',
                            'vpn'
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
                ])
                ->when($registrationDateFrom, function ($query) use ($registrationDateFrom) {
                    $query->whereHas('user', function ($query) use ($registrationDateFrom) {
                        $query->where('signed_up_at', '>=', Carbon::parse($registrationDateFrom));
                    });
                })
                ->when($registrationDateTo, function ($query) use ($registrationDateTo) {
                    $query->whereHas('user', function ($query) use ($registrationDateTo) {
                        $query->where('signed_up_at', '<=', Carbon::parse($registrationDateTo));
                    });
                })
                ->when($ipAddress, function ($query) use ($ipAddress) {
                    $query->where('ip_address', '=', $ipAddress);
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('user', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username . '%');
                    });
                })
                ->when($name, function ($query) use ($name) {
                    $query->whereHas('user.billing', function ($query) use ($name) {
                        $query->where('first_name', 'LIKE', '%'. $name . '%')
                            ->where('last_name', 'LIKE', '%'. $name . '%');
                    });
                })
                ->when($statusesIds, function ($query) use ($statusesIds) {
                    $query->whereHas('user', function ($query) use ($statusesIds) {
                        $query->whereIn('account_status_id', $statusesIds);
                    });
                })
                ->when($location, function ($query) use ($location) {
                    $query->whereHas('user.currentCityPlace', function ($query) use ($location) {
                        $query->where('name->en', 'LIKE', '%'. $location . '%');
                    })->orWhereHas('user.currentCityPlace.countryPlace', function ($query) use ($location) {
                        $query->where('name->en', 'LIKE', '%'. $location . '%');
                    });
                })
                ->when($vpn, function ($query) use ($vpn) {
                    $query->where('vpn', '=', $vpn);
                })
                ->when($duplicates, function ($query) use ($duplicates) {
                    //TODO: Figure it out about "duplicates"
                })
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipRegistrationList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $registrationDateFrom
     * @param string|null $registrationDateTo
     * @param string|null $ipAddress
     * @param string|null $username
     * @param string|null $name
     * @param array|null $statusesIds
     * @param string|null $location
     * @param bool|null $vpn
     * @param string|null $duplicates
     * @param int|null $perPage
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginatedFiltered(
        ?string $registrationDateFrom = null,
        ?string $registrationDateTo = null,
        ?string $ipAddress = null,
        ?string $username = null,
        ?string $name = null,
        ?array $statusesIds = null,
        ?string $location = null,
        ?bool $vpn = null,
        ?string $duplicates = null,
        ?int $perPage = null,
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return IpRegistrationList::query()
                ->select([
                    'id',
                    'user_id',
                    'ip_address',
                    'vpn'
                ])
                ->with([
                    'user' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'account_status_id',
                            'current_city_place_id',
                            'username',
                            'signed_up_at'
                        ])->with([
                            'currentCityPlace.timezone'
                        ]);
                    },
                    'duplicates' => function ($query) {
                        $query->select([
                            'id',
                            'user_id',
                            'ip_address',
                            'vpn'
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
                ])
                ->when($registrationDateFrom, function ($query) use ($registrationDateFrom) {
                    $query->whereHas('user', function ($query) use ($registrationDateFrom) {
                        $query->where('signed_up_at', '>=', Carbon::parse($registrationDateFrom));
                    });
                })
                ->when($registrationDateTo, function ($query) use ($registrationDateTo) {
                    $query->whereHas('user', function ($query) use ($registrationDateTo) {
                        $query->where('signed_up_at', '<=', Carbon::parse($registrationDateTo));
                    });
                })
                ->when($ipAddress, function ($query) use ($ipAddress) {
                    $query->where('ip_address', '=', $ipAddress);
                })
                ->when($username, function ($query) use ($username) {
                    $query->whereHas('user', function ($query) use ($username) {
                        $query->where('username', 'LIKE', '%'. $username . '%');
                    });
                })
                ->when($name, function ($query) use ($name) {
                    $query->whereHas('user.billing', function ($query) use ($name) {
                        $query->where('first_name', 'LIKE', '%'. $name . '%')
                            ->orWhere('last_name', 'LIKE', '%'. $name . '%');
                    });
                })
                ->when($statusesIds, function ($query) use ($statusesIds) {
                    $query->whereHas('user', function ($query) use ($statusesIds) {
                        $query->whereIn('account_status_id', $statusesIds);
                    });
                })
                ->when($location, function ($query) use ($location) {
                    $query->whereHas('user.currentCityPlace', function ($query) use ($location) {
                        $query->where('name->en', 'LIKE', '%'. $location . '%');
                    })->orWhereHas('user.currentCityPlace.countryPlace', function ($query) use ($location) {
                        $query->where('name->en', 'LIKE', '%'. $location . '%');
                    });
                })
                ->when(!is_null($vpn), function ($query) use ($vpn) {
                    $query->where('vpn', '=', $vpn);
                })
                ->when($duplicates, function ($query) use ($duplicates) {
                    $query->whereHas('duplicates', function ($query) use ($duplicates) {
                        $query->whereHas('user', function ($query) use ($duplicates) {
                            $query->where('username', 'LIKE', '%'. $duplicates . '%');
                        });
                    });
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipRegistrationList.' . __FUNCTION__),
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
    public function checkUserExistence(
        User $user
    ) : bool
    {
        try {
            return IpRegistrationList::query()
                ->where('user_id', '=', $user->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipRegistrationList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $ipAddress
     * @param bool $vpn
     *
     * @return IpRegistrationList|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        string $ipAddress,
        bool $vpn
    ) : ?IpRegistrationList
    {
        try {
            return IpRegistrationList::query()->create([
                'user_id'    => $user->id,
                'ip_address' => trim($ipAddress),
                'vpn'        => $vpn
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipRegistrationList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param IpRegistrationList $ipRegistrationList
     * @param User $user
     * @param string $ipAddress
     * @param bool $vpn
     *
     * @return IpRegistrationList
     *
     * @throws DatabaseException
     */
    public function update(
        IpRegistrationList $ipRegistrationList,
        User $user,
        string $ipAddress,
        bool $vpn
    ) : IpRegistrationList
    {
        try {
            $ipRegistrationList->update([
                'user_id'    => $user->id,
                'ip_address' => $ipAddress ? trim($ipAddress) : $ipRegistrationList->ip_address,
                'vpn'        => $vpn ?: $ipRegistrationList->vpn
            ]);

            return $ipRegistrationList;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipRegistrationList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param IpRegistrationList $ipRegistrationList
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        IpRegistrationList $ipRegistrationList
    ) : bool
    {
        try {
            return $ipRegistrationList->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/ipRegistrationList.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
