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
            'array' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.array'),
            'price' => [
                'required' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.price.required'),
                'numeric'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'required' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.description.required'),
                'string'   => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'platforms_ids' => [
                'array' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.platforms_ids.array'),
                '*' => [
                    'required' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.platforms_ids.*.required'),
                    'integer'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.platforms_ids.*.integer'),
                    'exists'   => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.platforms_ids.*.exists')
                ]
            ],
            'enabled' => [
                'required' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'video_chat' => [
            'array' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.video_chat.array'),
            'price' => [
                'required' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.price.required'),
                'numeric'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'required' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.description.required'),
                'string'   => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'platforms_ids' => [
                'array' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.platforms_ids.array'),
                '*' => [
                    'required' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.platforms_ids.*.required'),
                    'integer'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.platforms_ids.*.integer'),
                    'exists'   => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.platforms_ids.*.exists')
                ]
            ],
            'enabled' => [
                'required' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'real_life' => [
            'array' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.real_life.array'),
            'price' => [
                'required' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.price.required'),
                'numeric'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'required' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.description.required'),
                'string'   => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'same_location' => [
                'required' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.real_life.same_location.required'),
                'boolean'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.real_life.same_location.boolean')
            ],
            'country_id' => [
                'integer' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.real_life.country_id.integer'),
                'exists'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.real_life.country_id.exists')
            ],
            'region_id' => [
                'integer' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.real_life.region_id.integer'),
                'exists'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.real_life.region_id.exists')
            ],
            'region_suggestion' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.real_life.region_suggestion.string')
            ],
            'city_id' => [
                'integer' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.real_life.city_id.integer'),
                'exists'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.real_life.city_id.exists')
            ],
            'city_suggestion' => [
                'string' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.real_life.city_suggestion.string')
            ],
            'enabled' => [
                'required' => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.general.vybe.step.updateSecondStepNext.appearance_cases.voice_chat.enabled.boolean')
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find'           => __('validations.api.general.vybe.step.updateSecondStepNext.result.error.find'),
            'publishRequest' => __('validations.api.general.vybe.step.updateSecondStepNext.result.error.publishRequest'),
            'owner'          => __('validations.api.general.vybe.step.updateSecondStepNext.result.error.owner'),
            'completed'      => __('validations.api.general.vybe.step.updateSecondStepNext.result.error.completed'),
            'stepForward'    => __('validations.api.general.vybe.step.updateSecondStepNext.result.error.stepForward')
        ],
        'success' => __('validations.api.general.vybe.step.updateSecondStepNext.result.success')
    ]
];