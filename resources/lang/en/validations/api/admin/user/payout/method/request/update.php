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

    'method_id' => [
        'required' => __('validations.api.admin.user.payout.method.request.update.method_id.required'),
        'integer'  => __('validations.api.admin.user.payout.method.request.update.method_id.integer'),
        'exists'   => __('validations.api.admin.user.payout.method.request.update.method_id.exists')
    ],
    'request_status_id' => [
        'required' => __('validations.api.admin.user.payout.method.request.update.request_status_id.required'),
        'integer'  => __('validations.api.admin.user.payout.method.request.update.request_status_id.integer'),
        'in'       => __('validations.api.admin.user.payout.method.request.update.request_status_id.in')
    ],
    'toast_message_text' => [
        'boolean' => __('validations.api.admin.user.payout.method.request.update.toast_message_text.boolean')
    ],
    'result' => [
        'error' => [
            'find'  => [
                'user'                => __('validations.api.admin.user.payout.method.request.update.result.error.find.user'),
                'paymentMethod'       => __('validations.api.admin.user.payout.method.request.update.result.error.find.paymentMethod'),
                'payoutMethodRequest' => __('validations.api.admin.user.payout.method.request.update.result.error.find.payoutMethodRequest')
            ]
        ],
        'success' => __('validations.api.admin.user.payout.method.request.update.result.success')
    ]
];