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
                'user'                     => __('validations.api.general.profile.home.personalityTraitVote.result.error.find.user'),
                'personalityTraitListItem' => __('validations.api.general.profile.home.personalityTraitVote.result.error.find.personalityTraitListItem'),
                'personalityTrait'         => __('validations.api.general.profile.home.personalityTraitVote.result.error.find.personalityTrait')
            ],
            'yourself' => __('validations.api.general.profile.home.personalityTraitVote.result.error.yourself'),
            'exists'   => __('validations.api.general.profile.home.personalityTraitVote.result.error.exists')
        ],
        'success' => __('validations.api.general.profile.home.personalityTraitVote.result.success')
    ]
];