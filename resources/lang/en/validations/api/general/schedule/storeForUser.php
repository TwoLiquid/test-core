<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api General Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'schedule_id' => [
        'required' => __('validations.api.general.schedule.storeForUser.schedule_id.required'),
        'integer'  => __('validations.api.general.schedule.storeForUser.schedule_id.integer'),
        'exists'   => __('validations.api.general.schedule.storeForUser.schedule_id.exists')
    ],
    'status_id' => [
        'required' => __('validations.api.general.schedule.storeForUser.status_id.required'),
        'integer'  => __('validations.api.general.schedule.storeForUser.status_id.integer')
    ],
    'executed_at' => [
        'required'    => __('validations.api.general.schedule.storeForUser.executed_at.required'),
        'date_format' => __('validations.api.general.schedule.storeForUser.executed_at.date_format')
    ],
    'result' => [
        'error' => [
            'find' => [
                'schedule'       => __('validations.api.general.schedule.storeForUser.result.error.find.schedule'),
                'scheduleStatus' => __('validations.api.general.schedule.storeForUser.result.error.find.scheduleStatus'),
            ]
        ],
        'success' => __('validations.api.general.schedule.storeForUser.result.success')
    ]
];