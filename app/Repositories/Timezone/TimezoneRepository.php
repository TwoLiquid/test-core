<?php

namespace App\Repositories\Timezone;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Timezone\Timezone;
use App\Models\MySql\Timezone\TimezoneOffset;
use App\Repositories\BaseRepository;
use App\Repositories\Timezone\Interfaces\TimezoneRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class TimezoneRepository
 *
 * @package App\Repositories\Timezone
 */
class TimezoneRepository extends BaseRepository implements TimezoneRepositoryInterface
{
    /**
     * TimezoneRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.timezone.perPage');
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
    ) : ?Timezone
    {
        try {
            return Timezone::query()
                ->with([
                    'offsets'
                ])
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezone.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $externalId
     *
     * @return Timezone|null
     *
     * @throws DatabaseException
     */
    public function findByExternalId(
        string $externalId
    ) : ?Timezone
    {
        try {
            return Timezone::query()
                ->with([
                    'offsets'
                ])
                ->where('external_id', '=', trim($externalId))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezone.' . __FUNCTION__),
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
            return Timezone::query()
                ->with([
                    'offsets'
                ])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezone.' . __FUNCTION__),
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
            return Timezone::query()
                ->with([
                    'offsets'
                ])
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezone.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllHaveDst() : Collection
    {
        try {
            return Timezone::query()
                ->with([
                    'offsets',
                    'timeChanges'
                ])
                ->where('has_dst', '=', true)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezone.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ids
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $ids
    ) : Collection
    {
        try {
            return Timezone::query()
                ->with([
                    'offsets',
                    'timeChanges'
                ])
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezone.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $externalId
     * @param bool $hasDst
     * @param bool $inDst
     *
     * @return Timezone|null
     *
     * @throws DatabaseException
     */
    public function store(
        string $externalId,
        bool $hasDst,
        bool $inDst
    ) : ?Timezone
    {
        try {
            return Timezone::query()->create([
                'external_id' => $externalId,
                'has_dst'     => $hasDst,
                'in_dst'      => $inDst
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezone.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timezone $timezone
     * @param string|null $externalId
     * @param bool|null $hasDst
     * @param bool|null $inDst
     *
     * @return Timezone
     *
     * @throws DatabaseException
     */
    public function update(
        Timezone $timezone,
        ?string $externalId,
        ?bool $hasDst,
        ?bool $inDst
    ) : Timezone
    {
        try {
            $timezone->update([
                'external_id' => $externalId ?: $timezone->external_id,
                'has_dst'     => $hasDst ?: $timezone->has_dst,
                'in_dst'      => $inDst ?: $timezone->in_dst
            ]);

            return $timezone;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezone.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timezone $timezone
     * @param bool $inDst
     *
     * @return Timezone
     *
     * @throws DatabaseException
     */
    public function updateInDst(
        Timezone $timezone,
        bool $inDst
    ) : Timezone
    {
        try {
            $timezone->update([
                'in_dst' => $inDst
            ]);

            return $timezone;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezone.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timezone $timezone
     * @param TimezoneOffset $timezoneOffset
     * @param bool $isDst
     *
     * @return void
     *
     * @throws DatabaseException
     */
    public function attachOffset(
        Timezone $timezone,
        TimezoneOffset $timezoneOffset,
        bool $isDst = false
    ) : void
    {
        try {
            $timezone->offsets()->sync([
                $timezoneOffset->id => [
                    'is_dst' => $isDst
                ]
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezone.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timezone $timezone
     * @param TimezoneOffset $timezoneOffset
     *
     * @return void
     *
     * @throws DatabaseException
     */
    public function detachOffset(
        Timezone $timezone,
        TimezoneOffset $timezoneOffset
    ) : void
    {
        try {
            $timezone->offsets()->detach([
                $timezoneOffset->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezone.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Timezone $timezone
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Timezone $timezone
    ) : bool
    {
        try {
            return $timezone->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/timezone/timezone.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
