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
        'required' => __('validations.api.general.dashboard.wallet.addFunds.executePayment.hash.required'),
        'string'   => __('validations.api.general.dashboard.wallet.addFunds.executePayment.hash.string')
    ],
    'result' => [
        'error'   => [
            'find'   => __('validations.api.general.dashboard.wallet.addFunds.executePayment.result.error.find'),
            'owner'  => __('validations.api.general.dashboard.wallet.addFunds.executePayment.result.error.owner'),
            'hash'   => __('validations.api.general.dashboard.wallet.addFunds.executePayment.result.error.hash'),
            'status' => __('validations.api.general.dashboard.wallet.addFunds.executePayment.result.error.status')
        ],
        'success' => __('validations.api.general.dashboard.wallet.addFunds.executePayment.result.success')
    ]
];
