<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api General Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'result' => [
        'error' => [
            'find' => [
                'user' => __('validations.api.general.profile.vybe.show.result.error.find.user'),
                'vybe' => __('validations.api.general.profile.vybe.show.result.error.find.vybe')
            ],
            'belongsToUser' => __('validations.api.general.profile.vybe.show.result.error.belongsToUser'),
            'private'       => __('validations.api.general.profile.vybe.show.result.error.private'),
            'access'        => __('validations.api.general.profile.vybe.show.result.error.access')
        ],
        'success' => __('validations.api.general.profile.vybe.show.result.success')
    ]
];