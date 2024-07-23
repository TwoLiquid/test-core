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

    'name' => [
        'array' => __('validations.api.admin.csau.unit.update.name.array')
    ],
    'duration' => [
        'integer' => __('validations.api.admin.csau.unit.update.duration.integer')
    ],
    'visible' => [
        'boolean' => __('validations.api.admin.csau.unit.update.visible.boolean')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.csau.unit.update.result.error.find')
        ],
        'success' => __('validations.api.admin.csau.unit.update.result.success')
    ]
];