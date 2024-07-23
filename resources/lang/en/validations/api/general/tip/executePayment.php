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
        'required' => __('validations.api.general.tip.executePayment.hash.required'),
        'string'   => __('validations.api.general.tip.executePayment.hash.string')
    ],
    'result' => [
        'error' => [
            'find'   => __('validations.api.general.tip.executePayment.result.error.find'),
            'buyer'  => __('validations.api.general.tip.executePayment.result.error.buyer'),
            'hash'   => __('validations.api.general.tip.executePayment.result.error.hash'),
            'status' => __('validations.api.general.tip.executePayment.result.error.status')
        ],
        'success' => __('validations.api.general.tip.executePayment.result.success')
    ]
];
