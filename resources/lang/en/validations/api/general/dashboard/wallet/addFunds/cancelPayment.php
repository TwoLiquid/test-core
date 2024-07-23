<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Admin Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'hash' => [
        'required' => __('validations.api.general.dashboard.wallet.addFunds.cancelPayment.hash.required'),
        'string'   => __('validations.api.general.dashboard.wallet.addFunds.cancelPayment.hash.string')
    ],
    'result' => [
        'error'   => [
            'find'   => __('validations.api.general.dashboard.wallet.addFunds.cancelPayment.result.error.find'),
            'owner'  => __('validations.api.general.dashboard.wallet.addFunds.cancelPayment.result.error.owner'),
            'hash'   => __('validations.api.general.dashboard.wallet.addFunds.cancelPayment.result.error.hash'),
            'status' => __('validations.api.general.dashboard.wallet.addFunds.cancelPayment.result.error.status')
        ],
        'success' => __('validations.api.general.dashboard.wallet.addFunds.cancelPayment.result.success')
    ]
];