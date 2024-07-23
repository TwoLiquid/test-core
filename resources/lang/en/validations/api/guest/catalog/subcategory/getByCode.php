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
    'code' => [
        'required'  => __('validations.api.guest.catalog.subcategory.getByCode.code.required'),
        'string'    => __('validations.api.guest.catalog.subcategory.getByCode.code.string')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.guest.catalog.subcategory.getByCode.result.error.find')
        ],
        'success' => __('validations.api.guest.catalog.subcategory.getByCode.result.success')
    ]
];