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
            'find'   => __('validations.api.general.dashboard.purchase.acceptRescheduleRequest.result.error.find'),
            'buyer'  => __('validations.api.general.dashboard.purchase.acceptRescheduleRequest.result.error.buyer'),
            'status' => __('validations.api.general.dashboard.purchase.acceptRescheduleRequest.result.error.status'),
            'rescheduleRequest' => [
                'find'    => __('validations.api.general.dashboard.purchase.acceptRescheduleRequest.result.error.rescheduleRequest.find'),
                'opening' => __('validations.api.general.dashboard.purchase.acceptRescheduleRequest.result.error.rescheduleRequest.opening')
            ],
            'timeslot' => [
                'busy'   => __('validations.api.general.dashboard.purchase.acceptRescheduleRequest.result.error.timeslot.busy'),
                'full'   => __('validations.api.general.dashboard.purchase.acceptRescheduleRequest.result.error.timeslot.full'),
                'create' => __('validations.api.general.dashboard.purchase.acceptRescheduleRequest.result.error.timeslot.create')
            ]
        ],
        'success' => __('validations.api.general.dashboard.purchase.acceptRescheduleRequest.result.success')
    ]
];