<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Guest Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'activity_id' => [
        'integer' => __('validations.api.guest.profile.home.getVybes.activity_id.integer'),
        'exists'  => __('validations.api.guest.profile.home.getVybes.activity_id.exists'),
    ],
    'vybe_sort_id' => [
        'integer' => __('validations.api.guest.profile.home.getVybes.vybe_sort_id.integer'),
        'between' => __('validations.api.guest.profile.home.getVybes.vybe_sort_id.between'),
    ],
    'vybe_types_ids' => [
        'array' => __('validations.api.guest.profile.home.getVybes.vybe_types_ids.array'),
        '*' => [
            'integer' => __('validations.api.guest.profile.home.getVybes.vybe_types_ids.*.integer'),
            'between' => __('validations.api.guest.profile.home.getVybes.vybe_types_ids.*.between')
        ]
    ],
    'page' => [
        'integer' => __('validations.api.guest.profile.home.getVybes.page.integer')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.guest.profile.home.getVybes.result.error.find')
        ],
        'success' => __('validations.api.guest.profile.home.getVybes.result.success')
    ]
];