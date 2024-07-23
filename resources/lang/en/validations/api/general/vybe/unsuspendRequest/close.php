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
            'find'   => [
                'vybe'                 => __('validations.api.general.vybe.unsuspendRequest.close.result.error.find.vybe'),
                'vybeUnsuspendRequest' => __('validations.api.general.vybe.unsuspendRequest.close.result.error.find.vybeUnsuspendRequest')
            ],
            'owner'  => __('validations.api.general.vybe.unsuspendRequest.close.result.error.owner'),
            'status' => __('validations.api.general.vybe.unsuspendRequest.close.result.error.status')
        ],
        'success' => __('validations.api.general.vybe.unsuspendRequest.close.result.success')
    ]
];