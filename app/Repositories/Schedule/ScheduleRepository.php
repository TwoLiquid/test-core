<?php

namespace App\Repositories\Schedule;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Schedule;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Schedule\Interfaces\ScheduleRepositoryInterface;
use Carbon\Carbon;
use Exception;

/**
 * Class ScheduleRepository
 *
 * @package App\Repositories\Schedule
 */
class ScheduleRepository extends BaseRepository implements ScheduleRepositoryInterface
{
    /**
     * ScheduleRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.schedule.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Schedule|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Schedule
    {
        try {
            return Schedule::query()
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/schedule.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string $dateTimeFrom
     * @param string $dateTimeTo
     *
     * @return Schedule|null
     *
     * @throws DatabaseException
     */
    public function findByDatesForVybe(
        Vybe $vybe,
        string $dateTimeFrom,
        string $dateTimeTo
    ) : ?Schedule
    {
        try {
            return Schedule::query()
                ->where('vybe_id', '=', $vybe->id)
                ->where('datetime_from', '=', $dateTimeFrom)
                ->where('datetime_to', '=', $dateTimeTo)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/schedule.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string $dateTimeFrom
     * @param string $dateTimeTo
     *
     * @return Schedule|null
     *
     * @throws DatabaseException
     */
    public function store(
        Vybe $vybe,
        string $dateTimeFrom,
        string $dateTimeTo
    ) : ?Schedule
    {
        try {
            return Schedule::query()->create([
                'vybe_id'       => $vybe->id,
                'datetime_from' => Carbon::parse($dateTimeFrom)->format('Y-m-d H:i:s'),
                'datetime_to'   => Carbon::parse($dateTimeTo)->format('Y-m-d  H:i:s')
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/schedule.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Schedule $schedule
     * @param Vybe|null $vybe
     * @param string|null $dateTimeFrom
     * @param string|null $dateTimeTo
     *
     * @return Schedule
     *
     * @throws DatabaseException
     */
    public function update(
        Schedule $schedule,
        ?Vybe $vybe,
        ?string $dateTimeFrom,
        ?string $dateTimeTo
    ) : Schedule
    {
        try {
            $schedule->update([
                'vybe_id'       => $vybe ? $vybe->id : $schedule->vybe_id,
                'datetime_from' => $dateTimeFrom ? Carbon::parse($dateTimeFrom)->format('Y-m-d  H:i:s') : $schedule->datetime_from,
                'datetime_to'   => $dateTimeTo ? Carbon::parse($dateTimeTo)->format('Y-m-d  H:i:s') : $schedule->datetime_to
            ]);

            return $schedule;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/schedule.' . __FUNCTION__),
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
    public function deleteForVybe(
        Vybe $vybe
    ) : bool
    {
        try {
            return Schedule::query()
                ->where('vybe_id', '=', $vybe->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/schedule.' . __FUNCTION__),
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
    public function deleteForceForVybe(
        Vybe $vybe
    ) : bool
    {
        try {
            return Schedule::query()
                ->where('vybe_id', '=', $vybe->id)
                ->forceDelete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/schedule.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Schedule $schedule
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Schedule $schedule
    ) : bool
    {
        try {
            return $schedule->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/schedule.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}