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
        'required' => __('validations.api.admin.csau.unit.event.store.name.required'),
        'array'    => __('validations.api.admin.csau.unit.event.store.name.array')
    ],
    'visible' => [
        'required' => __('validations.api.admin.csau.unit.event.store.visible.required'),
        'boolean'  => __('validations.api.admin.csau.unit.event.store.visible.boolean')
    ],
    'result' => [
        'error' => [
            'create' => __('validations.api.admin.csau.unit.event.store.result.error.create')
        ],
        'success' => __('validations.api.admin.csau.unit.event.store.result.success')
    ]
];