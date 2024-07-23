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
            'find'   => __('validations.api.general.dashboard.purchase.cancelOrder.result.error.find'),
            'buyer'  => __('validations.api.general.dashboard.purchase.cancelOrder.result.error.buyer'),
            'status' => __('validations.api.general.dashboard.purchase.cancelOrder.result.error.status'),
            'invoice' => [
                'create' => __('validations.api.general.dashboard.purchase.cancelOrder.result.error.invoice.create')
            ]
        ],
        'success' => __('validations.api.general.dashboard.purchase.cancelOrder.result.success')
    ]
];