<?php

namespace App\Transformers\Api\General\Tip;

use App\Models\MySql\Timeslot;
use App\Transformers\BaseTransformer;

/**
 * Class TimeslotTransformer
 *
 * @package App\Transformers\Api\General\Tip
 */
class TimeslotTransformer extends BaseTransformer
{
    /**
     * @param Timeslot $timeslot
     *
     * @return array
     */
    public function transform(Timeslot $timeslot) : array
    {
        return [
            'start_date' => $timeslot->datetime_from ?
                $timeslot->datetime_from->format('d.m.Y') :
                null,
            'start_time' => $timeslot->datetime_from ?
                $timeslot->datetime_from->format('H:i') :
                null,
            'end_date' => $timeslot->datetime_to ?
                $timeslot->datetime_to->format('d.m.Y') :
                null,
            'end_time' => $timeslot->datetime_to ?
                $timeslot->datetime_to->format('H:i') :
                null
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'timeslot';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'timeslots';
    }
}
