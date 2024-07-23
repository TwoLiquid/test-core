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

    'country_status_id' => [
        'required' => __('validations.api.admin.user.billing.changeRequest.update.country_status_id.required'),
        'integer'  => __('validations.api.admin.user.billing.changeRequest.update.country_status_id.integer'),
        'in'       => __('validations.api.admin.user.billing.changeRequest.update.country_status_id.in')
    ],
    'toast_message_text' => [
        'boolean' => __('validations.api.admin.user.billing.changeRequest.update.toast_message_text.boolean')
    ],
    'result' => [
        'error' => [
            'find' => [
                'user'                 => __('validations.api.admin.user.billing.changeRequest.update.result.error.find.user'),
                'billingChangeRequest' => __('validations.api.admin.user.billing.changeRequest.update.result.error.find.billingChangeRequest')
            ]
        ],
        'success' => __('validations.api.admin.user.billing.changeRequest.update.result.success')
    ]
];