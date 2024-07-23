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

    'email' => [
        'required' => __('validations.api.guest.auth.initializePasswordReset.email.required'),
        'email'    => __('validations.api.guest.auth.initializePasswordReset.email.email'),
        'exists'   => __('validations.api.guest.auth.initializePasswordReset.email.exists')
    ],
    'result' => [
        'success' => __('validations.api.guest.auth.initializePasswordReset.result.success')
    ]
];
