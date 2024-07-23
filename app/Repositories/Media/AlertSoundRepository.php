<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Alert\Alert;
use App\Models\MySql\Media\AlertSound;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\AlertSoundRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class AlertSoundRepository
 *
 * @package App\Repositories\Media
 */
class AlertSoundRepository extends BaseRepository implements AlertSoundRepositoryInterface
{
    /**
     * AlertSoundRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.alertSound.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return AlertSound|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?AlertSound
    {
        try {
            return AlertSound::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/alertSound.' . __FUNCTION__),
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
            return AlertSound::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/alertSound.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return AlertSound::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/alertSound.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Alert $alert
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByAlert(
        Alert $alert
    ) : Collection
    {
        try {
            return AlertSound::query()
                ->where('alert_id', '=', $alert->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/alertSound.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $alerts
     * 
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByAlerts(
        Collection $alerts
    ) : Collection
    {
        try {
            return AlertSound::query()
                ->whereIn('alert_id', $alerts->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/alertSound.' . __FUNCTION__),
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
            return AlertSound::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/alertSound.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}