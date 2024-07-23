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
        'required' => __('validations.api.general.vybe.step.updateFirstStepChanges.title.required'),
        'string'   => __('validations.api.general.vybe.step.updateFirstStepChanges.title.string'),
        'min'      => __('validations.api.general.vybe.step.updateFirstStepChanges.title.min'),
        'max'      => __('validations.api.general.vybe.step.updateFirstStepChanges.title.max')
    ],
    'category_suggestion' => [
        'string' => __('validations.api.general.vybe.step.updateFirstStepChanges.category_suggestion.string')
    ],
    'subcategory_suggestion' => [
        'string' => __('validations.api.general.vybe.step.updateFirstStepChanges.subcategory_suggestion.string')
    ],
    'devices_ids' => [
        'array' => __('validations.api.general.vybe.step.updateFirstStepChanges.devices_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.step.updateFirstStepChanges.devices_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.step.updateFirstStepChanges.devices_ids.*.integer')
        ]
    ],
    'device_suggestion' => [
        'string' => __('validations.api.general.vybe.step.updateFirstStepChanges.device_suggestion.string')
    ],
    'activity_id' => [
        'integer' => __('validations.api.general.vybe.step.updateFirstStepChanges.activity_id.integer'),
        'exists'  => __('validations.api.general.vybe.step.updateFirstStepChanges.activity_id.exists')
    ],
    'activity_suggestion' => [
        'string' => __('validations.api.general.vybe.step.updateFirstStepChanges.activity_suggestion.string')
    ],
    'period_id' => [
        'integer' => __('validations.api.general.vybe.step.updateFirstStepChanges.period_id.integer'),
        'between' => __('validations.api.general.vybe.step.updateFirstStepChanges.period_id.between')
    ],
    'user_count' => [
        'integer' => __('validations.api.general.vybe.step.updateFirstStepChanges.user_count.integer')
    ],
    'result' => [
        'error' => [
            'find'           => __('validations.api.general.vybe.step.updateFirstStepChanges.result.error.find'),
            'publishRequest' => __('validations.api.general.vybe.step.updateFirstStepChanges.result.error.publishRequest'),
            'owner'          => __('validations.api.general.vybe.step.updateFirstStepChanges.result.error.owner'),
            'completed'      => __('validations.api.general.vybe.step.updateFirstStepChanges.result.error.completed'),
            'userCount'      => [
                'max' => __('validations.api.general.vybe.step.updateFirstStepChanges.result.error.userCount.max')
            ]
        ],
        'success' => __('validations.api.general.vybe.step.updateFirstStepChanges.result.success')
    ]
];