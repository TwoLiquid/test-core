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

    'account_status_status_id' => [
        'required' => __('validations.api.admin.user.request.unsuspend.update.account_status_status_id.required'),
        'integer'  => __('validations.api.admin.user.request.unsuspend.update.account_status_status_id.integer'),
        'in'       => __('validations.api.admin.user.request.unsuspend.update.account_status_status_id.in')
    ],
    'toast_message_text' => [
        'boolean' => __('validations.api.admin.user.request.unsuspend.update.toast_message_text.boolean')
    ],
    'result' => [
        'error' => [
            'find'  => [
                'user'             => __('validations.api.admin.user.request.unsuspend.update.result.error.find.user'),
                'unsuspendRequest' => __('validations.api.admin.user.request.unsuspend.update.result.error.find.unsuspendRequest')
            ]
        ],
        'success' => __('validations.api.admin.user.request.unsuspend.update.result.success')
    ]
];
