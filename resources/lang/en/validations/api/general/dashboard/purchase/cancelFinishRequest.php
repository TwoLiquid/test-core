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
            'find'          => __('validations.api.general.dashboard.purchase.cancelFinishRequest.result.error.find'),
            'seller'        => __('validations.api.general.dashboard.purchase.cancelFinishRequest.result.error.seller'),
            'status'        => __('validations.api.general.dashboard.purchase.cancelFinishRequest.result.error.status'),
            'finishRequest' => __('validations.api.general.dashboard.purchase.cancelFinishRequest.result.error.finishRequest')
        ],
        'success' => __('validations.api.general.dashboard.purchase.markAsFinished.result.success')
    ]
];