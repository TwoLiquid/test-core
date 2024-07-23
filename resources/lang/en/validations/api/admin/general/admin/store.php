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
        'required' => __('validations.api.admin.general.admin.store.first_name.required'),
        'string'   => __('validations.api.admin.general.admin.store.first_name.string')
    ],
    'last_name' => [
        'required' => __('validations.api.admin.general.admin.store.last_name.required'),
        'string'   => __('validations.api.admin.general.admin.store.last_name.string')
    ],
    'email' => [
        'required' => __('validations.api.admin.general.admin.store.email.required'),
        'regex'    => __('validations.api.admin.general.admin.store.email.regex'),
        'unique'   => __('validations.api.admin.general.admin.store.email.unique')
    ],
    'status_id' => [
        'required' => __('validations.api.admin.general.admin.store.status_id.required'),
        'integer'  => __('validations.api.admin.general.admin.store.status_id.integer'),
        'in'       => __('validations.api.admin.general.admin.store.status_id.in')
    ],
    'avatar' => [
        'array' => __('validations.api.admin.general.admin.store.avatar.array'),
        'content' => [
            'string' => __('validations.api.admin.general.admin.store.avatar.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.general.admin.store.avatar.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.general.admin.store.avatar.mime.string')
        ]
    ],
    'result' => [
        'error' => [
            'create' => __('validations.api.admin.general.admin.store.result.error.create')
        ],
        'success' => __('validations.api.admin.general.admin.store.result.success')
    ]
];