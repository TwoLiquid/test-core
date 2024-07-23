<?php

namespace App\Transformers\Api\Admin\Invoice\Buyer\OrderItem;

use App\Models\MySql\Timeslot;
use App\Transformers\BaseTransformer;

/**
 * Class OrderItemTimeslotTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Buyer\OrderItem
 */
class OrderItemTimeslotTransformer extends BaseTransformer
{
    /**
     * @param Timeslot $timeslot
     *
     * @return array
     */
    public function transform(Timeslot $timeslot) : array
    {
        return [
            'datetime_from' => $timeslot->datetime_from ?
                $timeslot->datetime_from->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'datetime_to' => $timeslot->datetime_to ?
                $timeslot->datetime_to->format('Y-m-d\TH:i:s.v\Z') :
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
