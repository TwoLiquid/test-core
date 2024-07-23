<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequestSchedule;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybePublishRequestScheduleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class VybePublishRequestScheduleRepository
 *
 * @package App\Repositories\Vybe
 */
class VybePublishRequestScheduleRepository extends BaseRepository implements VybePublishRequestScheduleRepositoryInterface
{
    /**
     * VybePublishRequestScheduleRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybePublishRequestSchedule.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return VybePublishRequestSchedule|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?VybePublishRequestSchedule
    {
        try {
            return VybePublishRequestSchedule::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestSchedule.' . __FUNCTION__),
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
            return VybePublishRequestSchedule::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestSchedule.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequest $vybePublishRequest
     * @param Carbon $dateTimeFrom
     * @param Carbon $dateTimeTo
     *
     * @return VybePublishRequestSchedule|null
     *
     * @throws DatabaseException
     */
    public function store(
        VybePublishRequest $vybePublishRequest,
        Carbon $dateTimeFrom,
        Carbon $dateTimeTo
    ) : ?VybePublishRequestSchedule
    {
        try {
            return VybePublishRequestSchedule::query()->create([
                'vybe_publish_request_id' => $vybePublishRequest->_id,
                'datetime_from'           => $dateTimeFrom,
                'datetime_to'             => $dateTimeTo
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestSchedule.' . __FUNCTION__),
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
    public function deleteForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : bool
    {
        try {
            return VybePublishRequestSchedule::query()
                ->where('vybe_publish_request_id', '=', $vybePublishRequest->_id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestSchedule.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybePublishRequestSchedule $vybePublishRequestSchedule
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VybePublishRequestSchedule $vybePublishRequestSchedule
    ) : bool
    {
        try {
            return $vybePublishRequestSchedule->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybePublishRequestSchedule.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
