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
            'find'    => [
                'paymentMethod'       => 'Failed to find а payout method',
                'payoutMethodRequest' => 'Failed to find а payout method request'
            ],
            'exists'  => __('validations.api.general.setting.payout.method.destroy.result.error.exists'),
            'pending' => __('validations.api.general.setting.payout.method.destroy.result.error.pending'),
            'status'  => 'Payout method request is not declined'
        ],
        'success' => __('validations.api.general.setting.payout.method.destroy.result.success')
    ]
];
