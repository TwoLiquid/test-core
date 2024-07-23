<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Auth Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'username' => [
        'required' => __('validations.api.guest.auth.register.username.required'),
        'string'   => __('validations.api.guest.auth.register.username.string'),
        'regex'    => __('validations.api.guest.auth.register.username.regex'),
        'min'      => __('validations.api.guest.auth.register.username.min'),
        'max'      => __('validations.api.guest.auth.register.username.min'),
        'unique'   => __('validations.api.guest.auth.register.username.unique')
    ],
    'email' => [
        'required' => __('validations.api.guest.auth.register.email.required'),
        'regex'    => __('validations.api.guest.auth.register.email.regex'),
        'unique'   => __('validations.api.guest.auth.register.email.unique')
    ],
    'gender_id' => [
        'required' => __('validations.api.guest.auth.register.gender_id.required'),
        'integer'  => __('validations.api.guest.auth.register.gender_id.integer')
    ],
    'birth_date' => [
        'required' => __('validations.api.guest.auth.register.birth_date.required'),
        'string'   => __('validations.api.guest.auth.register.birth_date.string')
    ],
    'password' => [
        'required' => __('validations.api.guest.auth.register.password.required'),
        'string'   => __('validations.api.guest.auth.register.password.string')
    ],
    'password_confirm' => [
        'required' => __('validations.api.guest.auth.register.password_confirm.required'),
        'string'   => __('validations.api.guest.auth.register.password_confirm.string')
    ],
    'country_place_id' => [
        'required' => __('validations.api.guest.auth.register.country_place_id.required'),
        'integer'  => __('validations.api.guest.auth.register.country_place_id.integer'),
        'exists'   => __('validations.api.guest.auth.register.country_place_id.exists')
    ],
    'hide_gender' => [
        'boolean' => __('validations.api.guest.auth.register.hide_gender.boolean')
    ],
    'hide_age' => [
        'boolean' => __('validations.api.guest.auth.register.hide_age.boolean')
    ],
    'result' => [
        'error' => [
            'credentials' => [
                'mismatch' => __('validations.api.guest.auth.register.result.error.credentials.mismatch')
            ],
            'birthDate' => [
                'young' => __('validations.api.guest.auth.register.result.error.birthDate.young')
            ],
            'create' => [
                'user' => __('validations.api.guest.auth.register.result.error.create.user')
            ]
        ],
        'success' => __('validations.api.guest.auth.register.result.success')
    ]
];