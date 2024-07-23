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
        'required' => __('validations.api.admin.csau.unit.store.name.required'),
        'array'    => __('validations.api.admin.csau.unit.store.name.array')
    ],
    'duration' => [
        'required' => __('validations.api.admin.csau.unit.store.duration.required'),
        'integer'  => __('validations.api.admin.csau.unit.store.duration.integer')
    ],
    'visible' => [
        'required' => __('validations.api.admin.csau.unit.store.visible.required'),
        'boolean'  => __('validations.api.admin.csau.unit.store.visible.boolean')
    ],
    'result' => [
        'error' => [
            'create' => __('validations.api.admin.csau.unit.store.result.error.create')
        ],
        'success' => __('validations.api.admin.csau.unit.store.result.success')
    ]
];