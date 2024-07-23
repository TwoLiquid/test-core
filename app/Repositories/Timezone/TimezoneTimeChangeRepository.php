<?php

namespace App\Repositories\Timezone;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Timezone\Timezone;
use App\Models\MySql\Timezone\TimezoneTimeChange;
use App\Repositories\BaseRepository;
use App\Repositories\Timezone\Interfaces\TimezoneTimeChangeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class TimezoneTimeChangeRepository
 *
 * @package App\Repositories\Timezone
 */
class TimezoneTimeChangeRepository extends BaseRepository implements TimezoneTimeChangeRepositoryInterface
{
    /**
     * TimezoneTimeChangeRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.timezoneTimeChange.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Timezone|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?TimezoneTimeChange
    {
        try {
            return TimezoneTimeChange::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneTimeChange.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timezone $timezone
     * @param Carbon $dateTime
     *
     * @return TimezoneTimeChange|null
     *
     * @throws DatabaseException
     */
    public function findByTimezone(
        Timezone $timezone,
        Carbon $dateTime
    ) : ?TimezoneTimeChange
    {
        try {
            return TimezoneTimeChange::query()
                ->where('timezone_id', '=', $timezone->id)
                ->where('started_at', '<=', $dateTime->format('Y-m-d H:i:s'))
                ->where('completed_at', '>=', $dateTime->format('Y-m-d H:i:s'))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneTimeChange.' . __FUNCTION__),
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
            return TimezoneTimeChange::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneTimeChange.' . __FUNCTION__),
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
            return TimezoneTimeChange::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneTimeChange.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timezone $timezone
     * @param bool $toDst
     * @param Carbon $startedAt
     * @param Carbon $completedAt
     *
     * @return TimezoneTimeChange|null
     *
     * @throws DatabaseException
     */
    public function store(
        Timezone $timezone,
        bool $toDst,
        Carbon $startedAt,
        Carbon $completedAt
    ) : ?TimezoneTimeChange
    {
        try {
            return TimezoneTimeChange::query()->create([
                'timezone_id'  => $timezone->id,
                'to_dst'       => $toDst,
                'started_at'   => $startedAt->format('Y-m-d H:i:s'),
                'completed_at' => $completedAt->format('Y-m-d H:i:s')
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneTimeChange.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TimezoneTimeChange $timezoneTimeChange
     * @param Timezone|null $timezone
     * @param bool|null $toDst
     * @param Carbon|null $startedAt
     * @param Carbon|null $completedAt
     *
     * @return TimezoneTimeChange
     *
     * @throws DatabaseException
     */
    public function update(
        TimezoneTimeChange $timezoneTimeChange,
        ?Timezone $timezone,
        ?bool $toDst,
        ?Carbon $startedAt,
        ?Carbon $completedAt
    ) : TimezoneTimeChange
    {
        try {
            $timezoneTimeChange->update([
                'timezone_id' => $timezone ? $timezone->id : $timezoneTimeChange->timezone_id,
                'to_dst'      => $toDst ?: $timezoneTimeChange->to_dst,
                'started_at'  => $startedAt ?
                    $startedAt->format('Y-m-d H:i:s') :
                    $timezoneTimeChange->started_at,
                'completed_at'  => $completedAt ?
                    $completedAt->format('Y-m-d H:i:s') :
                    $timezoneTimeChange->completed_at
            ]);

            return $timezoneTimeChange;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneTimeChange.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TimezoneTimeChange $timezoneTimeChange
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        TimezoneTimeChange $timezoneTimeChange
    ) : bool
    {
        try {
            return $timezoneTimeChange->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneTimeChange.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
