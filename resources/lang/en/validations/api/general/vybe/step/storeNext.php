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
        'required' => __('validations.api.general.vybe.step.storeNext.title.required'),
        'string'   => __('validations.api.general.vybe.step.storeNext.title.string'),
        'min'      => __('validations.api.general.vybe.step.storeNext.title.min'),
        'max'      => __('validations.api.general.vybe.step.storeNext.title.max')
    ],
    'category_suggestion' => [
        'string' => __('validations.api.general.vybe.step.storeNext.category_suggestion.string')
    ],
    'subcategory_suggestion' => [
        'string' => __('validations.api.general.vybe.step.storeNext.subcategory_suggestion.string')
    ],
    'devices_ids' => [
        'array' => __('validations.api.general.vybe.step.storeNext.devices_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.step.storeNext.devices_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.step.storeNext.devices_ids.*.integer')
        ]
    ],
    'device_suggestion' => [
        'string' => __('validations.api.general.vybe.step.storeNext.device_suggestion.string')
    ],
    'activity_id' => [
        'integer' => __('validations.api.general.vybe.step.storeNext.activity_id.integer'),
        'exists'  => __('validations.api.general.vybe.step.storeNext.activity_id.exists')
    ],
    'activity_suggestion' => [
        'string' => __('validations.api.general.vybe.step.storeNext.activity_suggestion.string')
    ],
    'period_id' => [
        'required' => __('validations.api.general.vybe.step.storeNext.period_id.required'),
        'integer'  => __('validations.api.general.vybe.step.storeNext.period_id.integer'),
        'between'  => __('validations.api.general.vybe.step.storeNext.period_id.between')
    ],
    'user_count' => [
        'required' => __('validations.api.general.vybe.step.storeNext.user_count.required'),
        'integer'  => __('validations.api.general.vybe.step.storeNext.user_count.integer')
    ],
    'result' => [
        'error' => [
            'category' => [
                'absence'  => __('validations.api.general.vybe.step.storeNext.result.error.category.absence'),
                'doubling' => __('validations.api.general.vybe.step.storeNext.result.error.category.doubling')
            ],
            'activity' => [
                'absence'  => __('validations.api.general.vybe.step.storeNext.result.error.activity.absence'),
                'doubling' => __('validations.api.general.vybe.step.storeNext.result.error.activity.doubling')
            ],
            'userCount' => [
                'max' => __('validations.api.general.vybe.step.storeNext.result.error.userCount.max')
            ]
        ],
        'success' => __('validations.api.general.vybe.step.storeNext.result.success')
    ]
];