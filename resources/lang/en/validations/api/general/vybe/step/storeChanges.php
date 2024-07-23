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
        'required' => __('validations.api.general.vybe.step.storeChanges.title.required'),
        'string'   => __('validations.api.general.vybe.step.storeChanges.title.string'),
        'min'      => __('validations.api.general.vybe.step.storeChanges.title.min'),
        'max'      => __('validations.api.general.vybe.step.storeChanges.title.max')
    ],
    'category_suggestion' => [
        'string' => __('validations.api.general.vybe.step.storeChanges.category_suggestion.string')
    ],
    'subcategory_suggestion' => [
        'string' => __('validations.api.general.vybe.step.storeChanges.subcategory_suggestion.string')
    ],
    'devices_ids' => [
        'array' => __('validations.api.general.vybe.step.storeChanges.devices_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.step.storeChanges.devices_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.step.storeChanges.devices_ids.*.integer')
        ]
    ],
    'device_suggestion' => [
        'string' => __('validations.api.general.vybe.step.storeChanges.device_suggestion.string')
    ],
    'activity_id' => [
        'integer' => __('validations.api.general.vybe.step.storeChanges.activity_id.integer'),
        'exists'  => __('validations.api.general.vybe.step.storeChanges.activity_id.exists')
    ],
    'activity_suggestion' => [
        'string' => __('validations.api.general.vybe.step.storeChanges.activity_suggestion.string')
    ],
    'period_id' => [
        'integer' => __('validations.api.general.vybe.step.storeChanges.period_id.integer'),
        'between' => __('validations.api.general.vybe.step.storeChanges.period_id.between')
    ],
    'user_count' => [
        'integer' => __('validations.api.general.vybe.step.storeChanges.user_count.integer')
    ],
    'result' => [
        'error' => [
            'userCount' => [
                'max' => __('validations.api.general.vybe.step.storeChanges.result.error.userCount.max')
            ],
            'create' => __('validations.api.general.vybe.step.storeChanges.result.error.create')
        ],
        'success' => __('validations.api.general.vybe.step.storeChanges.result.success')
    ]
];