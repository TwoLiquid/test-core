<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequestSchedule;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybeChangeRequestScheduleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class VybeChangeRequestScheduleRepository
 *
 * @package App\Repositories\Vybe
 */
class VybeChangeRequestScheduleRepository extends BaseRepository implements VybeChangeRequestScheduleRepositoryInterface
{
    /**
     * VybeChangeRequestScheduleRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybeChangeRequestSchedule.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return VybeChangeRequestSchedule|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?VybeChangeRequestSchedule
    {
        try {
            return VybeChangeRequestSchedule::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestSchedule.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : Collection
    {
        try {
            return VybeChangeRequestSchedule::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestSchedule.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Carbon $dateTimeFrom
     * @param Carbon $dateTimeTo
     *
     * @return VybeChangeRequestSchedule|null
     *
     * @throws DatabaseException
     */
    public function store(
        VybeChangeRequest $vybeChangeRequest,
        Carbon $dateTimeFrom,
        Carbon $dateTimeTo
    ) : ?VybeChangeRequestSchedule
    {
        try {
            return VybeChangeRequestSchedule::query()->create([
                'vybe_change_request_id' => $vybeChangeRequest->_id,
                'datetime_from'          => $dateTimeFrom,
                'datetime_to'            => $dateTimeTo
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestSchedule.' . __FUNCTION__),
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
    public function deleteForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : bool
    {
        try {
            return VybeChangeRequestSchedule::query()
                ->where('vybe_change_request_id', '=', $vybeChangeRequest->_id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestSchedule.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeChangeRequestSchedule $vybeChangeRequestSchedule
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VybeChangeRequestSchedule $vybeChangeRequestSchedule
    ) : bool
    {
        try {
            return $vybeChangeRequestSchedule->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeChangeRequestSchedule.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
