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

    'user_personality_trait_id' => [
        'required'  => __('validations.api.general.personalityTrait.vote.store.user_personality_trait_id.required'),
        'integer'   => __('validations.api.general.personalityTrait.vote.store.user_personality_trait_id.integer'),
        'exists'    => __('validations.api.general.personalityTrait.vote.store.user_personality_trait_id.exists')
    ],
    'voter_id' => [
        'required'  => __('validations.api.general.personalityTrait.vote.store.voter_id.required'),
        'integer'   => __('validations.api.general.personalityTrait.vote.store.voter_id.integer'),
        'exists'    => __('validations.api.general.personalityTrait.vote.store.voter_id.exists')
    ],
    'result' => [
        'error' => [
            'find'      => [
                'personalityTraitListItem' => __('validations.api.general.personalityTrait.vote.store.result.error.find.personalityTraitListItem'),
                'personalityTrait'         => __('validations.api.general.personalityTrait.vote.store.result.error.find.personalityTrait'),
                'user'                     => __('validations.api.general.personalityTrait.vote.store.result.error.find.user')
            ],
            'existence' => __('validations.api.general.personalityTrait.vote.store.result.error.existence'),
            'create'    => __('validations.api.general.personalityTrait.vote.store.result.error.create')
        ],
        'success' => __('validations.api.general.personalityTrait.vote.store.result.success')
    ]
];