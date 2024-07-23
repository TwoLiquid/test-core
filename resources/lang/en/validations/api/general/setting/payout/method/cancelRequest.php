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

    'result' => [
        'error' => [
            'find' => [
                'paymentMethod'       => __('validations.api.general.setting.payout.method.cancelRequest.result.error.find.paymentMethod'),
                'payoutMethodRequest' => __('validations.api.general.setting.payout.method.cancelRequest.result.error.find.payoutMethodRequest'),
            ]
        ],
        'success' => __('validations.api.general.setting.payout.method.cancelRequest.result.success')
    ]
];