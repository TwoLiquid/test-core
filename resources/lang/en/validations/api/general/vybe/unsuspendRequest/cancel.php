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
                'vybe'             => __('validations.api.general.vybe.unsuspendRequest.cancel.result.error.find.vybe'),
                'unsuspendRequest' => __('validations.api.general.vybe.unsuspendRequest.cancel.result.error.find.unsuspendRequest')
            ],
            'owner'     => __('validations.api.general.vybe.unsuspendRequest.cancel.result.error.owner'),
            'completed' => __('validations.api.general.vybe.unsuspendRequest.cancel.result.error.completed')
        ],
        'success' => __('validations.api.general.vybe.unsuspendRequest.cancel.result.success')
    ]
];