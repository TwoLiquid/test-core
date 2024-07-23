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

    'status_status_id' => [
        'integer' => __('validations.api.admin.vybe.deletionRequest.update.status_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.deletionRequest.update.status_status_id.in')
    ],
    'toast_message_text' => [
        'boolean' => __('validations.api.admin.vybe.deletionRequest.update.toast_message_text.boolean')
    ],
    'result' => [
        'error' => [
            'find'    => __('validations.api.admin.vybe.deletionRequest.update.result.error.find'),
            'pending' => __('validations.api.admin.vybe.deletionRequest.update.result.error.pending')
        ],
        'success' => __('validations.api.admin.vybe.deletionRequest.update.result.success')
    ]
];
