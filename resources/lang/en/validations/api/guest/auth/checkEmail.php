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
        'required' => __('validations.api.guest.auth.checkEmail.email.required'),
        'regex'    => __('validations.api.guest.auth.checkEmail.email.regex'),
        'unique'   => __('validations.api.guest.auth.checkEmail.email.unique')
    ],
    'result' => [
        'success' => __('validations.api.guest.auth.checkEmail.result.success')
    ]
];