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
        'required' => __('validations.api.guest.auth.checkUsername.username.required'),
        'string'   => __('validations.api.guest.auth.checkUsername.username.string'),
        'unique'   => __('validations.api.guest.auth.checkUsername.username.unique')
    ],
    'result' => [
        'success' => __('validations.api.guest.auth.checkUsername.result.success')
    ]
];