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
        'error' => [
            'find'   => __('validations.api.general.dashboard.purchase.declineRescheduleRequest.result.error.find'),
            'buyer'  => __('validations.api.general.dashboard.purchase.declineRescheduleRequest.result.error.buyer'),
            'status' => __('validations.api.general.dashboard.purchase.declineRescheduleRequest.result.error.status'),
            'rescheduleRequest' => [
                'find'    => __('validations.api.general.dashboard.purchase.declineRescheduleRequest.result.error.rescheduleRequest.find'),
                'opening' => __('validations.api.general.dashboard.purchase.declineRescheduleRequest.result.error.rescheduleRequest.opening')
            ]
        ],
        'success' => __('validations.api.general.dashboard.purchase.declineRescheduleRequest.result.success')
    ]
];