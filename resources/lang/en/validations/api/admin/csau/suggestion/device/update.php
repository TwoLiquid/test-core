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

    'suggestion' => [
        'string' => __('validations.api.admin.csau.suggestion.device.update.suggestion.string')
    ],
    'status_id' => [
        'required' => __('validations.api.admin.csau.suggestion.device.update.status_id.required'),
        'integer'  => __('validations.api.admin.csau.suggestion.device.update.status_id.integer'),
        'between'  => __('validations.api.admin.csau.suggestion.device.update.status_id.between')
    ],
    'visible' => [
        'required' => __('validations.api.admin.csau.suggestion.device.update.visible.required'),
        'boolean'  => __('validations.api.admin.csau.suggestion.device.update.visible.boolean')
    ],
    'result' => [
        'error' => [
            'find'      => __('validations.api.admin.csau.suggestion.device.update.result.error.find'),
            'processed' => __('validations.api.admin.csau.suggestion.device.update.result.error.processed'),
            'create'    => __('validations.api.admin.csau.suggestion.device.update.result.error.create')
        ],
        'success' => __('validations.api.admin.csau.suggestion.device.update.result.success')
    ]
];
