<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Service Exception Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the exception class
    |
    */

    'isAvailable' => [
        'vybe' => [
            'owner' => __('exceptions.service.timeslot.isAvailable.vybe.owner')
        ],
        'schedule' => [
            'accessible' => __('exceptions.service.timeslot.isAvailable.schedule.accessible')
        ],
        'timeslot' => [
            'past'       => __('exceptions.service.timeslot.isAvailable.timeslot.past'),
            'range'      => __('exceptions.service.timeslot.isAvailable.timeslot.range'),
            'busy'       => __('exceptions.service.timeslot.isAvailable.timeslot.busy'),
            'full'       => __('exceptions.service.timeslot.isAvailable.timeslot.full'),
            'accessible' => __('exceptions.service.timeslot.isAvailable.timeslot.accessible')
        ]
    ]
];