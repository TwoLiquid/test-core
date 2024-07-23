<?php

namespace App\Transformers\Api\Admin\Vybe\Vybe\Schedule;

use App\Models\MySql\Schedule;
use App\Transformers\BaseTransformer;

/**
 * Class ScheduleTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\Vybe\Schedule
 */
class ScheduleTransformer extends BaseTransformer
{
    /**
     * @param Schedule $schedule
     *
     * @return array
     */
    public function transform(Schedule $schedule) : array
    {
        return [
            'id'            => $schedule->id,
            'datetime_from' => $schedule->datetime_from ? $schedule->datetime_from->format('Y-m-d\TH:i:s.v\Z') : null,
            'datetime_to'   => $schedule->datetime_to ? $schedule->datetime_to->format('Y-m-d\TH:i:s.v\Z') : null
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'schedule';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'schedules';
    }
}
