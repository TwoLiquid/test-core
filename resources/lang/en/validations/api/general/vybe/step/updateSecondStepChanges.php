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

    // 2-nd step
    'appearance_cases' => [
        'voice_chat' => [
            'array' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.array'),
            'price' => [
                'numeric' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'platforms_ids' => [
                'array' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.platforms_ids.array'),
                '*' => [
                    'required' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.platforms_ids.*.required'),
                    'integer'  => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.platforms_ids.*.integer'),
                    'exists'   => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.platforms_ids.*.exists')
                ]
            ],
            'enabled' => [
                'boolean' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'video_chat' => [
            'array' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.video_chat.array'),
            'price' => [
                'numeric' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'platforms_ids' => [
                'array' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.platforms_ids.array'),
                '*' => [
                    'required' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.platforms_ids.*.required'),
                    'integer'  => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.platforms_ids.*.integer'),
                    'exists'   => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.platforms_ids.*.exists')
                ]
            ],
            'enabled' => [
                'boolean' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'real_life' => [
            'array' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.real_life.array'),
            'price' => [
                'numeric' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'same_location' => [
                'boolean' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.real_life.same_location.boolean')
            ],
            'country_id' => [
                'integer' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.real_life.country_id.integer'),
                'exists'  => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.real_life.country_id.exists')
            ],
            'region_id' => [
                'integer' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.real_life.region_id.integer'),
                'exists'  => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.real_life.region_id.exists')
            ],
            'region_suggestion' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.real_life.region_suggestion.string')
            ],
            'city_id' => [
                'integer' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.real_life.city_id.integer'),
                'exists'  => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.real_life.city_id.exists')
            ],
            'city_suggestion' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.real_life.city_suggestion.string')
            ],
            'enabled' => [
                'boolean' => __('validations.api.general.vybe.step.updateSecondStepChanges.appearance_cases.voice_chat.enabled.boolean')
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find'           => __('validations.api.general.vybe.step.updateSecondStepChanges.result.error.find'),
            'publishRequest' => __('validations.api.general.vybe.step.updateSecondStepChanges.result.error.publishRequest'),
            'owner'          => __('validations.api.general.vybe.step.updateSecondStepChanges.result.error.owner'),
            'completed'      => __('validations.api.general.vybe.step.updateSecondStepChanges.result.error.completed')
        ],
        'success' => __('validations.api.general.vybe.step.updateSecondStepChanges.result.success')
    ]
];