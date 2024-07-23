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
    'status_id' => [
        'required'  => __('validations.api.admin.invoice.withdrawal.request.update.status_id.required'),
        'integer'   => __('validations.api.admin.invoice.withdrawal.request.update.status_id.integer'),
        'in'        => __('validations.api.admin.invoice.withdrawal.request.update.status_id.in')
    ],
    'toast_message_text' => [
        'required_if'   => __('validations.api.admin.invoice.withdrawal.request.update.toast_message_text.required_if'),
        'string'        => __('validations.api.admin.invoice.withdrawal.request.update.toast_message_text.string')
    ],
    'method_id' => [
        'integer'       => __('validations.api.admin.invoice.withdrawal.request.update.method_id.integer'),
        'exists'        => __('validations.api.admin.invoice.withdrawal.request.update.method_id.exists')
    ],
    'result' => [
        'error' => [
            'find'          => __('validations.api.admin.invoice.withdrawal.request.update.result.error.find'),
            'status'        => __('validations.api.admin.invoice.withdrawal.request.update.result.error.status')
        ],
        'success' => __('validations.api.admin.invoice.withdrawal.request.update.result.success')
    ]
];