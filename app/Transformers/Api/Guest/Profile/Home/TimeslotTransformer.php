<?php

namespace App\Transformers\Api\Guest\Profile\Home;

use App\Models\MySql\Timeslot;
use App\Transformers\BaseTransformer;

/**
 * Class TimeslotTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home
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
            'id'            => $timeslot->id,
            'datetime_from' => $timeslot->datetime_from ?
                $timeslot->datetime_from->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'datetime_to'   => $timeslot->datetime_to ?
                $timeslot->datetime_to->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'users_count'   => $timeslot->users_count ? $timeslot->users_count : null
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
