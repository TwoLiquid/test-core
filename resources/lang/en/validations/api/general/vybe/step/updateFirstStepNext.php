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

    // 1-st step
    'title' => [
        'required' => __('validations.api.general.vybe.step.updateFirstStepNext.title.required'),
        'string'   => __('validations.api.general.vybe.step.updateFirstStepNext.title.string'),
        'min'      => __('validations.api.general.vybe.step.updateFirstStepNext.title.min'),
        'max'      => __('validations.api.general.vybe.step.updateFirstStepNext.title.max')
    ],
    'category_suggestion' => [
        'string' => __('validations.api.general.vybe.step.updateFirstStepNext.category_suggestion.string')
    ],
    'subcategory_suggestion' => [
        'string' => __('validations.api.general.vybe.step.updateFirstStepNext.subcategory_suggestion.string')
    ],
    'devices_ids' => [
        'array' => __('validations.api.general.vybe.step.updateFirstStepNext.devices_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.step.updateFirstStepNext.devices_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.step.updateFirstStepNext.devices_ids.*.integer')
        ]
    ],
    'device_suggestion' => [
        'string' => __('validations.api.general.vybe.step.updateFirstStepNext.device_suggestion.string')
    ],
    'activity_id' => [
        'integer' => __('validations.api.general.vybe.step.updateFirstStepNext.activity_id.integer'),
        'exists'  => __('validations.api.general.vybe.step.updateFirstStepNext.activity_id.exists')
    ],
    'activity_suggestion' => [
        'string' => __('validations.api.general.vybe.step.updateFirstStepNext.activity_suggestion.string')
    ],
    'period_id' => [
        'required' => __('validations.api.general.vybe.step.updateFirstStepNext.period_id.required'),
        'integer'  => __('validations.api.general.vybe.step.updateFirstStepNext.period_id.integer'),
        'between'  => __('validations.api.general.vybe.step.updateFirstStepNext.period_id.between')
    ],
    'user_count' => [
        'required' => __('validations.api.general.vybe.step.updateFirstStepNext.user_count.required'),
        'integer'  => __('validations.api.general.vybe.step.updateFirstStepNext.user_count.integer')
    ],
    'result' => [
        'error' => [
            'find'           => __('validations.api.general.vybe.step.updateFirstStepNext.result.error.find'),
            'publishRequest' => __('validations.api.general.vybe.step.updateFirstStepNext.result.error.publishRequest'),
            'owner'          => __('validations.api.general.vybe.step.updateFirstStepNext.result.error.owner'),
            'completed'      => __('validations.api.general.vybe.step.updateFirstStepNext.result.error.completed'),
            'userCount' => [
                'max' => __('validations.api.general.vybe.step.updateFirstStepNext.result.error.userCount.max')
            ],
            'category' => [
                'absence'  => __('validations.api.general.vybe.step.updateFirstStepNext.result.error.category.absence'),
                'doubling' => __('validations.api.general.vybe.step.updateFirstStepNext.result.error.category.doubling')
            ],
            'activity' => [
                'absence'  => __('validations.api.general.vybe.step.updateFirstStepNext.result.error.activity.absence'),
                'doubling' => __('validations.api.general.vybe.step.updateFirstStepNext.result.error.activity.doubling')
            ]
        ],
        'success' => __('validations.api.general.vybe.step.updateFirstStepNext.result.success')
    ]
];