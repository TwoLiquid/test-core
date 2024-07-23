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

    'content' => [
        'required' => __('validations.api.general.setting.userIdVerification.update.content.required'),
        'string'   => __('validations.api.general.setting.userIdVerification.update.content.string')
    ],
    'original_name' => [
        'string' => __('validations.api.general.setting.userIdVerification.update.original_name.string')
    ],
    'mime' => [
        'required' => __('validations.api.general.setting.userIdVerification.update.mime.required'),
        'string'   => __('validations.api.general.setting.userIdVerification.update.mime.string')
    ],
    'extension' => [
        'required' => __('validations.api.general.setting.userIdVerification.update.extension.required'),
        'string'   => __('validations.api.general.setting.userIdVerification.update.extension.string')
    ],
    'result' => [
        'error' => [
            'suspended'    => __('validations.api.general.setting.userIdVerification.update.result.error.suspended'),
            'verification' => __('validations.api.general.setting.userIdVerification.update.result.error.verification'),
            'create'       => __('validations.api.general.setting.userIdVerification.update.result.error.create')
        ],
        'success' => [
            'create' => __('validations.api.general.setting.userIdVerification.update.result.success.create'),
            'update' => __('validations.api.general.setting.userIdVerification.update.result.success.update')
        ]
    ]
];
