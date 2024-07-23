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
                'personalityTraitListItem' => __('validations.api.general.personalityTrait.vote.destroy.result.error.find.personalityTraitListItem'),
                'personalityTrait'         => __('validations.api.general.personalityTrait.vote.destroy.result.error.find.personalityTrait'),
                'user'                     => __('validations.api.general.personalityTrait.vote.destroy.result.error.find.user')
            ],
            'delete' => __('validations.api.general.personalityTrait.vote.destroy.result.error.delete'),
        ],
        'success' => __('validations.api.general.personalityTrait.vote.destroy.result.success')
    ]
];