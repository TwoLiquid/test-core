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
                'user'                 => __('validations.api.admin.user.billing.changeRequest.index.result.error.find.user'),
                'billingChangeRequest' => __('validations.api.admin.user.billing.changeRequest.index.result.error.find.billingChangeRequest')
            ]
        ],
        'success' => __('validations.api.admin.user.billing.changeRequest.index.result.success')
    ]
];