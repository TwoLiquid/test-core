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

    'first_name' => [
        'required' => __('validations.api.admin.general.admin.update.first_name.required'),
        'string'   => __('validations.api.admin.general.admin.update.first_name.string')
    ],
    'last_name' => [
        'required' => __('validations.api.admin.general.admin.update.last_name.required'),
        'string'   => __('validations.api.admin.general.admin.update.last_name.string')
    ],
    'email' => [
        'required' => __('validations.api.admin.general.admin.update.email.required'),
        'regex'    => __('validations.api.admin.general.admin.update.email.regex')
    ],
    'status_id' => [
        'required' => __('validations.api.admin.general.admin.update.status_id.required'),
        'integer'  => __('validations.api.admin.general.admin.update.status_id.integer'),
        'in'       => __('validations.api.admin.general.admin.update.status_id.in')
    ],
    'avatar' => [
        'array' => __('validations.api.admin.general.admin.update.avatar.array'),
        'content' => [
            'string' => __('validations.api.admin.general.admin.update.avatar.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.general.admin.update.avatar.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.general.admin.update.avatar.mime.string')
        ]
    ],
    'result' => [
        'error' => [
            'find'  => __('validations.api.admin.general.admin.update.result.error.find'),
            'email' => __('validations.api.admin.general.admin.update.result.error.email')
        ],
        'success' => __('validations.api.admin.general.admin.update.result.success')
    ]
];