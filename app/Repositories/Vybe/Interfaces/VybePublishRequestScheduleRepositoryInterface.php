<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequest;
use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequestSchedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybePublishRequestScheduleRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybePublishRequestScheduleRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybePublishRequestSchedule|null
     */
    public function findById(
        ?string $id
    ) : ?VybePublishRequestSchedule;

    /**
     * This method provides getting rows
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return Collection
     */
    public function getAllForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     * @param Carbon $dateTimeFrom
     * @param Carbon $dateTimeTo
     *
     * @return VybePublishRequestSchedule|null
     */
    public function store(
        VybePublishRequest $vybePublishRequest,
        Carbon $dateTimeFrom,
        Carbon $dateTimeTo
    ) : ?VybePublishRequestSchedule;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybePublishRequest $vybePublishRequest
     *
     * @return bool
     */
    public function deleteForVybePublishRequest(
        VybePublishRequest $vybePublishRequest
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybePublishRequestSchedule $vybePublishRequestSchedule
     *
     * @return bool
     */
    public function delete(
        VybePublishRequestSchedule $vybePublishRequestSchedule
    ) : bool;
}
