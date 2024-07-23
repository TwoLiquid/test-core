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
                'vybe'              => __('validations.api.general.vybe.changeRequest.close.result.error.find.vybe'),
                'vybeChangeRequest' => __('validations.api.general.vybe.changeRequest.close.result.error.find.vybeChangeRequest')
            ],
            'owner'  => __('validations.api.general.vybe.changeRequest.close.result.error.owner'),
            'status' => __('validations.api.general.vybe.changeRequest.close.result.error.status')
        ],
        'success' => __('validations.api.general.vybe.changeRequest.close.result.success')
    ]
];