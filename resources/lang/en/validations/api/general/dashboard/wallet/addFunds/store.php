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

    'result' => [
        'error'   => [
            'balance'    => __('validations.api.general.dashboard.wallet.addFunds.store.result.error.balance'),
            'create'     => __('validations.api.general.dashboard.wallet.addFunds.store.result.error.create'),
            'paymentUrl' => __('validations.api.general.dashboard.wallet.addFunds.store.result.error.paymentUrl')
        ],
        'success' => __('validations.api.general.dashboard.wallet.addFunds.store.result.success')
    ]
];