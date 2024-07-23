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
                'vybe'               => __('validations.api.general.vybe.publishRequest.close.result.error.find.vybe'),
                'vybePublishRequest' => __('validations.api.general.vybe.publishRequest.close.result.error.find.vybePublishRequest')
            ],
            'owner'  => __('validations.api.general.vybe.publishRequest.close.result.error.owner'),
            'status' => __('validations.api.general.vybe.publishRequest.close.result.error.status')
        ],
        'success' => __('validations.api.general.vybe.publishRequest.close.result.success')
    ]
];