<?php

namespace App\Services\Schedule\Interfaces;

use App\Models\MySql\Vybe\Vybe;
use Carbon\Carbon;

/**
 * Interface ScheduleServiceInterface
 *
 * @package App\Services\Schedule\Interfaces
 */
interface ScheduleServiceInterface
{
    /**
     * This method provides checking data
     *
     * @param Vybe $vybe
     * @param Carbon $datetimeFrom
     * @param Carbon $datetimeTo
     *
     * @return bool
     */
    public function isAccessible(
        Vybe $vybe,
        Carbon $datetimeFrom,
        Carbon $datetimeTo
    ) : bool;
}
