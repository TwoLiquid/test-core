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
        'required' => __('validations.api.admin.auth.passwordReset.email.required'),
        'regex'    => __('validations.api.admin.auth.passwordReset.email.regex'),
        'exists'   => __('validations.api.admin.auth.passwordReset.email.exists')
    ],
    'result' => [
        'success' => __('validations.api.admin.auth.passwordReset.result.success')
    ]
];