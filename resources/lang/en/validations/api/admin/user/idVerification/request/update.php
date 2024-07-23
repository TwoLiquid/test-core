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

    'verification_status_status_id' => [
        'required' => __('validations.api.admin.user.idVerification.request.update.verification_status_status_id.required'),
        'integer'  => __('validations.api.admin.user.idVerification.request.update.verification_status_status_id.integer'),
        'in'       => __('validations.api.admin.user.idVerification.request.update.verification_status_status_id.in')
    ],
    'verification_suspended' => [
        'required' => __('validations.api.admin.user.idVerification.request.update.verification_suspended.required'),
        'boolean'  => __('validations.api.admin.user.idVerification.request.update.verification_suspended.boolean')
    ],
    'toast_message_text' => [
        'boolean' => __('validations.api.admin.user.idVerification.request.update.toast_message_text.boolean')
    ],
    'result' => [
        'error' => [
            'find' => [
                'user'                  => __('validations.api.admin.user.idVerification.request.update.result.error.find.user'),
                'idVerificationRequest' => __('validations.api.admin.user.idVerification.request.update.result.error.find.idVerificationRequest')
            ],
        ],
        'success' => __('validations.api.admin.user.idVerification.request.update.result.success')
    ]
];