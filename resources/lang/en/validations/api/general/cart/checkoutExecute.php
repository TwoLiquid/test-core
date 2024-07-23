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
        'required' => __('validations.api.general.cart.checkoutExecute.hash.required'),
        'string'   => __('validations.api.general.cart.checkoutExecute.hash.string')
    ],
    'result' => [
        'error' => [
            'order' => [
                'find' => __('validations.api.general.cart.checkoutExecute.result.error.order.find')
            ],
            'buyer' => __('validations.api.general.cart.checkoutExecute.result.error.buyer'),
            'hash'  => __('validations.api.general.cart.checkoutExecute.result.error.hash'),
            'orderInvoice' => [
                'find'   => __('validations.api.general.cart.checkoutExecute.result.error.orderInvoice.find'),
                'status' => __('validations.api.general.cart.checkoutExecute.result.error.orderInvoice.status')
            ],
            'balance' => [
                'wrong'  => __('validations.api.general.cart.checkoutExecute.result.error.balance.wrong'),
                'enough' => __('validations.api.general.cart.checkoutExecute.result.error.balance.enough')
            ]
        ],
        'success' => __('validations.api.general.cart.checkoutExecute.result.success')
    ]
];
