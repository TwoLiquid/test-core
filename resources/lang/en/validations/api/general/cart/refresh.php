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

    'cart_items' => [
        'required' => __('validations.api.general.cart.refresh.cart_items.required'),
        'array'    => __('validations.api.general.cart.refresh.cart_items.array'),
        '*' => [
            'appearance_case_id' => [
                'required' => __('validations.api.general.cart.refresh.cart_items.*.appearance_case_id.required'),
                'integer'  => __('validations.api.general.cart.refresh.cart_items.*.appearance_case_id.integer'),
                'exists'   => __('validations.api.general.cart.refresh.cart_items.*.appearance_case_id.exists')
            ],
            'datetime_from' => [
                'date_format' => __('validations.api.general.cart.refresh.cart_items.*.datetime_from.date_format')
            ],
            'datetime_to' => [
                'date_format' => __('validations.api.general.cart.refresh.cart_items.*.datetime_to.date_format')
            ],
            'quantity' => [
                'required' => __('validations.api.general.cart.refresh.cart_items.*.quantity.required'),
                'integer'  => __('validations.api.general.cart.refresh.cart_items.*.quantity.integer')
            ]
        ]
    ],
    'result' => [
        'success' => __('validations.api.general.cart.refresh.result.success')
    ]
];