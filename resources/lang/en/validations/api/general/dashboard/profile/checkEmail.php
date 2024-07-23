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
        'required' => __('validations.api.general.dashboard.profile.checkEmail.email.required'),
        'regex'    => __('validations.api.general.dashboard.profile.checkEmail.email.regex'),
        'unique'   => __('validations.api.general.dashboard.profile.checkEmail.email.unique')
    ],
    'result' => [
        'success' => __('validations.api.general.dashboard.profile.checkEmail.result.success')
    ]
];