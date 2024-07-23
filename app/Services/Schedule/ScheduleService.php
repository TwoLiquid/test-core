<?php

namespace App\Services\Schedule;

use App\Models\MySql\Vybe\Vybe;
use App\Services\Schedule\Interfaces\ScheduleServiceInterface;
use App\Services\Vybe\VybeService;
use Carbon\Carbon;

/**
 * Class ScheduleService
 *
 * @package App\Services\Schedule
 */
class ScheduleService implements ScheduleServiceInterface
{
    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * ScheduleService constructor
     */
    public function __construct()
    {
        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
    }

    /**
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
    ) : bool
    {
        /**
         * Getting vybe calendar for order
         */
        $calendar = $this->vybeService->getScheduledCalendar(
            $vybe,
            Carbon::parse($datetimeFrom)
        );

        /** @var array $calendarDay */
        foreach ($calendar as $calendarDay) {

            /** @var array $schedule */
            foreach ($calendarDay['schedules'] as $schedule) {

                /**
                 * Checking accessible schedule range
                 */
                if ($datetimeFrom->format('Y-m-d\TH:i:s.v\Z') >= $schedule['datetime_from'] &&
                    $datetimeTo->format('Y-m-d\TH:i:s.v\Z') <= $schedule['datetime_to']
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}
