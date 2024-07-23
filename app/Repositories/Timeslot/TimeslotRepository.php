<?php

namespace App\Repositories\Timeslot;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Timeslot;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Timeslot\Interfaces\TimeslotRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class TimeslotRepository
 *
 * @package App\Repositories\Timeslot
 */
class TimeslotRepository extends BaseRepository implements TimeslotRepositoryInterface
{
    /**
     * TimeslotRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.timeslot.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Timeslot|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Timeslot
    {
        try {
            return Timeslot::query()
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string $datetimeFrom
     * @param string $datetimeTo
     *
     * @return Timeslot|null
     *
     * @throws DatabaseException
     */
    public function findForVybeBetweenDates(
        Vybe $vybe,
        string $datetimeFrom,
        string $datetimeTo
    ) : ?Timeslot
    {
        try {
            return Timeslot::query()
                ->with([
                    'users' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'username'
                        ]);
                    }
                ])
                ->withCount([
                    'users'
                ])
                ->whereHas('orderItems', function ($query) use ($vybe) {
                    $query->where('vybe_id', '=', $vybe->id);
                })
                ->where('datetime_from', '<=', Carbon::parse($datetimeFrom)->format('Y-m-d H:i:s'))
                ->where('datetime_to', '>=', Carbon::parse($datetimeTo)->format('Y-m-d H:i:s'))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string $datetimeFrom
     * @param string $datetimeTo
     *
     * @return Timeslot|null
     *
     * @throws DatabaseException
     */
    public function findForVybeByDates(
        Vybe $vybe,
        string $datetimeFrom,
        string $datetimeTo
    ) : ?Timeslot
    {
        try {
            return Timeslot::query()
                ->with([
                    'users' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'avatar_id',
                            'gender_id',
                            'label_id',
                            'state_status_id',
                            'account_status_id',
                            'username'
                        ])->withCount([
                            'vybes'
                        ]);
                    }
                ])
                ->withCount([
                    'users'
                ])
                ->whereHas('orderItems', function ($query) use ($vybe) {
                    $query->where('vybe_id', '=', $vybe->id);
                })
                ->where('datetime_from', '=', Carbon::parse($datetimeFrom)->format('Y-m-d H:i:s'))
                ->where('datetime_to', '=', Carbon::parse($datetimeTo)->format('Y-m-d H:i:s'))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
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
            return Timeslot::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
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
            return Timeslot::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param Carbon $datetimeFrom
     * @param Carbon $datetimeTo
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForVybeBetweenDates(
        Vybe $vybe,
        Carbon $datetimeFrom,
        Carbon $datetimeTo
    ) : Collection
    {
        try {
            return Timeslot::query()
                ->with([
                    'users' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'avatar_id',
                            'label_id',
                            'state_status_id',
                            'username'
                        ])->with([
                            'subscriptions' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            },
                            'subscribers' => function ($query) {
                                $query->select([
                                    'id',
                                    'auth_id',
                                    'username'
                                ]);
                            }
                        ])->withCount([
                            'vybes'
                        ]);
                    },
                    'orderItems.vybe' => function ($query) {
                        $query->select([
                            'id',
                            'user_count'
                        ]);
                    }
                ])
                ->withCount([
                    'users'
                ])
                ->whereHas('orderItems', function ($query) use ($vybe) {
                    $query->where('vybe_id', '=', $vybe->id);
                })
                ->whereDate('datetime_from', '>=', $datetimeFrom)
                ->whereDate('datetime_to', '<=', $datetimeTo)
                ->orderBy('datetime_from')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getFutureForVybe(
        Vybe $vybe
    ) : Collection
    {
        try {
            return Timeslot::query()
                ->with([
                    'users' => function ($query) {
                        $query->select([
                            'id',
                            'auth_id',
                            'avatar_id',
                            'label_id',
                            'state_status_id',
                            'username'
                        ]);
                    }
                ])
                ->withCount([
                    'users'
                ])
                ->whereHas('orderItems', function ($query) use ($vybe) {
                    $query->where('vybe_id', '=', $vybe->id);
                })
                ->when($vybe->getType()->isSolo() || $vybe->getType()->isGroup(), function ($query) use ($vybe) {
                    $query->whereDate('datetime_to', '<=', Carbon::now()->addDays($vybe->order_advance)->endOfDay());
                })
                ->whereDate('datetime_from', '>=', Carbon::now())
                ->orderBy('datetime_from', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $datetimeFrom
     * @param string $datetimeTo
     *
     * @return Timeslot|null
     *
     * @throws DatabaseException
     */
    public function store(
        string $datetimeFrom,
        string $datetimeTo
    ) : ?Timeslot
    {
        try {
            return Timeslot::query()->create([
                'datetime_from' => trim($datetimeFrom),
                'datetime_to'   => trim($datetimeTo)
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timeslot $timeslot
     * @param string $datetimeFrom
     * @param string $datetimeTo
     *
     * @return Timeslot
     *
     * @throws DatabaseException
     */
    public function update(
        Timeslot $timeslot,
        string $datetimeFrom,
        string $datetimeTo
    ) : Timeslot
    {
        try {
            $timeslot->update([
                'datetime_from' => $datetimeFrom ? trim($datetimeFrom) : $timeslot->datetime_from,
                'datetime_to'   => $datetimeTo ? trim($datetimeTo) : $timeslot->datetime_to
            ]);

            return $timeslot;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timeslot $timeslot
     * @param User $user
     *
     * @throws DatabaseException
     */
    public function attachUser(
        Timeslot $timeslot,
        User $user
    ) : void
    {
        try {
            $timeslot->users()->sync([
                $user->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timeslot $timeslot
     * @param array $usersIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachUsers(
        Timeslot $timeslot,
        array $usersIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $timeslot->users()->sync(
                $usersIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timeslot $timeslot
     * @param User $user
     *
     * @throws DatabaseException
     */
    public function detachUser(
        Timeslot $timeslot,
        User $user
    ) : void
    {
        try {
            $timeslot->users()->detach([
                $user->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timeslot $timeslot
     * @param array $usersIds
     *
     * @throws DatabaseException
     */
    public function detachUsers(
        Timeslot $timeslot,
        array $usersIds
    ) : void
    {
        try {
            $timeslot->users()->detach(
                $usersIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timeslot $timeslot
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Timeslot $timeslot
    ) : bool
    {
        try {
            return $timeslot->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timeslot.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
