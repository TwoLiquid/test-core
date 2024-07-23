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
            'find'    => __('validations.api.general.dashboard.sale.declineOrder.result.error.find'),
            'seller'  => __('validations.api.general.dashboard.sale.declineOrder.result.error.seller'),
            'status'  => __('validations.api.general.dashboard.sale.declineOrder.result.error.status'),
            'invoice' => [
                'create' => __('validations.api.general.dashboard.sale.declineOrder.result.error.invoice.create')
            ]
        ],
        'success' => __('validations.api.general.dashboard.sale.declineOrder.result.success')
    ]
];