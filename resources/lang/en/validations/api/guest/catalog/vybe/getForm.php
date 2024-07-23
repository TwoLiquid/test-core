<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Guest Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'category_id' => [
        'integer'   => __('validations.api.guest.catalog.vybe.getForm.category_id.integer'),
        'exists'    => __('validations.api.guest.catalog.vybe.getForm.category_id.exists')
    ],
    'result' => [
        'error' => [
            'find' => [
                'category' => __('validations.api.guest.catalog.vybe.getForm.result.error.find.category')
            ]
        ],
        'success' => __('validations.api.guest.catalog.vybe.getForm.result.success'),
    ]
];