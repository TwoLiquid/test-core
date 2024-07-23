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

    'method_id' => [
        'required' => __('validations.api.general.cart.checkout.method_id.required'),
        'integer'  => __('validations.api.general.cart.checkout.method_id.integer'),
        'exists'   => __('validations.api.general.cart.checkout.method_id.exists')
    ],
    'result' => [
        'error' => [
            'cartItems' => __('validations.api.general.cart.checkout.result.error.cartItems'),
            'balance' => [
                'wrong'  => __('validations.api.general.cart.checkout.result.error.balance.wrong'),
                'enough' => __('validations.api.general.cart.checkout.result.error.balance.enough')
            ],
            'create' => [
                'order' => __('validations.api.general.cart.checkout.result.error.create.order')
            ]
        ],
        'success' => __('validations.api.general.cart.checkout.result.success')
    ]
];
