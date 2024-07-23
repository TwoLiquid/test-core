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
        'required' => __('validations.api.general.vybe.rating.vote.store.vybe_id.required'),
        'integer'  => __('validations.api.general.vybe.rating.vote.store.vybe_id.integer'),
        'exists'   => __('validations.api.general.vybe.rating.vote.store.vybe_id.exists')
    ],
    'user_id' => [
        'required' => __('validations.api.general.vybe.rating.vote.store.user_id.required'),
        'integer'  => __('validations.api.general.vybe.rating.vote.store.user_id.integer'),
        'exists'   => __('validations.api.general.vybe.rating.vote.store.user_id.exists')
    ],
    'rating' => [
        'required' => __('validations.api.general.vybe.rating.vote.store.rating.required'),
        'integer'  => __('validations.api.general.vybe.rating.vote.store.rating.integer')
    ],
    'result' => [
        'error' => [
            'match' => [
                'user'  => __('validations.api.general.vybe.rating.vote.store.result.error.match.user')
            ],
            'existence' => __('validations.api.general.vybe.rating.vote.store.result.error.existence'),
            'create'    => __('validations.api.general.vybe.rating.vote.store.result.error.create')
        ],
        'success' => __('validations.api.general.vybe.rating.vote.store.result.success')
    ]
];