<?php

namespace App\Transformers\Api\Admin\Vybe\PublishRequest;

use App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequestSchedule;
use App\Transformers\BaseTransformer;

/**
 * Class VybePublishRequestScheduleTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\PublishRequest
 */
class VybePublishRequestScheduleTransformer extends BaseTransformer
{
    /**
     * @param VybePublishRequestSchedule $vybePublishRequestSchedule
     *
     * @return array
     */
    public function transform(VybePublishRequestSchedule $vybePublishRequestSchedule) : array
    {
        return [
            'id'            => $vybePublishRequestSchedule->_id,
            'datetime_from' => $vybePublishRequestSchedule->datetime_from ?
                $vybePublishRequestSchedule->datetime_from->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'datetime_to'   => $vybePublishRequestSchedule->datetime_to ?
                $vybePublishRequestSchedule->datetime_to->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_publish_request_schedule';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_publish_request_schedules';
    }
}
