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
        'required' => __('validations.api.general.dashboard.profile.checkUsername.username.required'),
        'string'   => __('validations.api.general.dashboard.profile.checkUsername.username.string'),
        'unique'   => __('validations.api.general.dashboard.profile.checkUsername.username.unique')
    ],
    'result' => [
        'success' => __('validations.api.general.dashboard.profile.checkUsername.result.success')
    ]
];