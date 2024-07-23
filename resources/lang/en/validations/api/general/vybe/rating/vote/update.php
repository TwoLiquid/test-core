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

    'vybe_id' => [
        'integer' => __('validations.api.general.vybe.rating.vote.update.vybe_id.integer'),
        'exists'  => __('validations.api.general.vybe.rating.vote.update.vybe_id.exists')
    ],
    'user_id' => [
        'integer' => __('validations.api.general.vybe.rating.vote.update.user_id.integer'),
        'exists'  => __('validations.api.general.vybe.rating.vote.update.user_id.exists')
    ],
    'rating' => [
        'integer' => __('validations.api.general.vybe.rating.vote.update.rating.integer')
    ],
    'result' => [
        'error' => [
            'find'      => __('validations.api.general.vybe.rating.vote.update.result.error.find'),
            'existence' => __('validations.api.general.vybe.rating.vote.update.result.error.existence')
        ],
        'success' => __('validations.api.general.vybe.rating.vote.update.result.success')
    ]
];