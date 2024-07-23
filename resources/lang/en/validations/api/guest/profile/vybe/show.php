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

    'result' => [
        'error' => [
            'find' => [
                'user' => __('validations.api.guest.profile.vybe.show.result.error.find.user'),
                'vybe' => __('validations.api.guest.profile.vybe.show.result.error.find.vybe')
            ],
            'belongsToUser' => __('validations.api.guest.profile.vybe.show.result.error.belongsToUser'),
            'vybeAgeLimit'  => __('validations.api.guest.profile.vybe.show.result.error.vybeAgeLimit')
        ],
        'success' => __('validations.api.guest.profile.vybe.show.result.success')
    ]
];