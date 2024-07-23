<?php

namespace App\Repositories\Timezone;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Timezone\Timezone;
use App\Models\MySql\Timezone\TimezoneOffset;
use App\Repositories\BaseRepository;
use App\Repositories\Timezone\Interfaces\TimezoneOffsetRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class TimezoneOffsetRepository
 *
 * @package App\Repositories\Timezone
 */
class TimezoneOffsetRepository extends BaseRepository implements TimezoneOffsetRepositoryInterface
{
    /**
     * TimezoneOffsetRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.timezoneOffset.perPage');
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
    ) : ?TimezoneOffset
    {
        try {
            return TimezoneOffset::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneOffset.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $name
     *
     * @return TimezoneOffset|null
     *
     * @throws DatabaseException
     */
    public function findByName(
        string $name
    ) : ?TimezoneOffset
    {
        try {
            return TimezoneOffset::query()
                ->where('name->en', '=', trim($name))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneOffset.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll(
    ) : Collection
    {
        try {
            return TimezoneOffset::all();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneOffset.' . __FUNCTION__),
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
            return TimezoneOffset::query()
                ->with([
                    'child',
                    'timeChanges'
                ])
                ->whereNull('parent_id')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneOffset.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $code
     * @param array $name
     * @param int $offset
     *
     * @return TimezoneOffset|null
     *
     * @throws DatabaseException
     */
    public function store(
        string $code,
        array $name,
        int $offset
    ) : ?TimezoneOffset
    {
        try {
            return TimezoneOffset::query()->create([
                'code'   => $code,
                'name'   => $name,
                'offset' => $offset
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneOffset.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TimezoneOffset $timezoneOffset
     * @param string|null $code
     * @param array|null $name
     * @param int|null $offset
     *
     * @return TimezoneOffset
     *
     * @throws DatabaseException
     */
    public function update(
        TimezoneOffset $timezoneOffset,
        ?string $code,
        ?array $name,
        ?int $offset
    ) : TimezoneOffset
    {
        try {
            $timezoneOffset->update([
                'name'   => $name ?: $timezoneOffset->name,
                'code'   => $code ?: $timezoneOffset->code,
                'offset' => $offset ?: $timezoneOffset->offset
            ]);

            return $timezoneOffset;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneOffset.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TimezoneOffset $timezoneOffset
     * @param Timezone $timezone
     * @param bool $isDst
     *
     * @return void
     *
     * @throws DatabaseException
     */
    public function attachTimezone(
        TimezoneOffset $timezoneOffset,
        Timezone $timezone,
        bool $isDst = false
    ) : void
    {
        try {
            $timezoneOffset->timezones()->sync([
                $timezone->id => [
                    'is_dst' => $isDst
                ]
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneOffset.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TimezoneOffset $timezoneOffset
     * @param Timezone $timezone
     *
     * @return void
     *
     * @throws DatabaseException
     */
    public function detachTimezone(
        TimezoneOffset $timezoneOffset,
        Timezone $timezone
    ) : void
    {
        try {
            $timezoneOffset->timezones()->detach([
                $timezone->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneOffset.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TimezoneOffset $timezoneOffset
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        TimezoneOffset $timezoneOffset
    ) : bool
    {
        try {
            return $timezoneOffset->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezoneOffset.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
