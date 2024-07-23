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

    'hash' => [
        'required' => __('validations.api.general.cart.checkoutCancel.hash.required'),
        'string'   => __('validations.api.general.cart.checkoutCancel.hash.string')
    ],
    'result' => [
        'error' => [
            'order' => [
                'find' => __('validations.api.general.cart.checkoutCancel.result.error.order.find')
            ],
            'buyer' => __('validations.api.general.cart.checkoutCancel.result.error.buyer'),
            'hash'  => __('validations.api.general.cart.checkoutCancel.result.error.hash'),
            'orderInvoice' => [
                'find'   => __('validations.api.general.cart.checkoutCancel.result.error.orderInvoice.find'),
                'status' => __('validations.api.general.cart.checkoutCancel.result.error.orderInvoice.status')
            ]
        ],
        'success' => __('validations.api.general.cart.checkoutCancel.result.success')
    ]
];
