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

    'message' => [
        'string' => __('validations.api.general.vybe.unpublishRequest.update.message.string')
    ],
    'result' => [
        'error' => [
            'find'      => __('validations.api.general.vybe.unpublishRequest.update.result.error.find'),
            'owner'     => __('validations.api.general.vybe.unpublishRequest.update.result.error.owner'),
            'completed' => __('validations.api.general.vybe.unpublishRequest.update.result.error.completed'),
            'status'    => __('validations.api.general.vybe.unpublishRequest.update.result.error.status'),
            'request'   => __('validations.api.general.vybe.unpublishRequest.update.result.error.request'),
            'create'    => __('validations.api.general.vybe.unpublishRequest.update.result.error.create')
        ],
        'success' => __('validations.api.general.vybe.unpublishRequest.update.result.success')
    ]
];