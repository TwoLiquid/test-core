<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequest;
use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequestSchedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeChangeRequestScheduleRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeChangeRequestScheduleRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeChangeRequestSchedule|null
     */
    public function findById(
        ?string $id
    ) : ?VybeChangeRequestSchedule;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return Collection
     */
    public function getAllForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     * @param Carbon $dateTimeFrom
     * @param Carbon $dateTimeTo
     *
     * @return VybeChangeRequestSchedule|null
     */
    public function store(
        VybeChangeRequest $vybeChangeRequest,
        Carbon $dateTimeFrom,
        Carbon $dateTimeTo
    ) : ?VybeChangeRequestSchedule;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeChangeRequest $vybeChangeRequest
     *
     * @return bool|null
     */
    public function deleteForVybeChangeRequest(
        VybeChangeRequest $vybeChangeRequest
    ) : ?bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeChangeRequestSchedule $vybeChangeRequestSchedule
     *
     * @return bool
     */
    public function delete(
        VybeChangeRequestSchedule $vybeChangeRequestSchedule
    ) : bool;
}
