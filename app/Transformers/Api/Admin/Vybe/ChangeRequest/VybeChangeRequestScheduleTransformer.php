<?php

namespace App\Transformers\Api\Admin\Vybe\ChangeRequest;

use App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequestSchedule;
use App\Transformers\BaseTransformer;

/**
 * Class VybeChangeRequestScheduleTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\ChangeRequest
 */
class VybeChangeRequestScheduleTransformer extends BaseTransformer
{
    /**
     * @param VybeChangeRequestSchedule $vybeChangeRequestSchedule
     *
     * @return array
     */
    public function transform(VybeChangeRequestSchedule $vybeChangeRequestSchedule) : array
    {
        return [
            'id'            => $vybeChangeRequestSchedule->_id,
            'datetime_from' => $vybeChangeRequestSchedule->datetime_from ?
                $vybeChangeRequestSchedule->datetime_from->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'datetime_to'   => $vybeChangeRequestSchedule->datetime_to ?
                $vybeChangeRequestSchedule->datetime_to->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_change_request_schedule';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_change_request_schedules';
    }
}
