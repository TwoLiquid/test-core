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
        'required' => __('validations.api.general.tip.cancelPayment.hash.required'),
        'string'   => __('validations.api.general.tip.cancelPayment.hash.string')
    ],
    'result' => [
        'error' => [
            'find'   => __('validations.api.general.tip.cancelPayment.result.error.find'),
            'buyer'  => __('validations.api.general.tip.cancelPayment.result.error.buyer'),
            'hash'   => __('validations.api.general.tip.cancelPayment.result.error.hash'),
            'status' => __('validations.api.general.tip.cancelPayment.result.error.status')
        ],
        'success' => __('validations.api.general.tip.cancelPayment.result.success')
    ]
];