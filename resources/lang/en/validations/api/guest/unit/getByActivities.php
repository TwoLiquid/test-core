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

    'activities_ids' => [
        'array' => __('validations.api.guest.unit.getByActivities.activities_ids.array'),
        '*' => [
            'integer' => __('validations.api.guest.unit.getByActivities.activities_ids.*.integer'),
            'exists'  => __('validations.api.guest.unit.getByActivities.activities_ids.*.exists')
        ]
    ],
    'result' => [
        'success' => __('validations.api.guest.unit.getByActivities.result.success')
    ]
];