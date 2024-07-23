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

    'category_suggestion' => [
        'array' => __('validations.api.admin.csau.suggestion.category.update.category_suggestion.array')
    ],
    'category_status_id' => [
        'integer'  => __('validations.api.admin.csau.suggestion.category.update.category_status_id.integer'),
        'between'  => __('validations.api.admin.csau.suggestion.category.update.category_status_id.between'),
        'declined' => __('validations.api.admin.csau.suggestion.category.update.category_status_id.declined')
    ],
    'subcategory_suggestion' => [
        'array' => __('validations.api.admin.csau.suggestion.category.update.subcategory_suggestion.array')
    ],
    'subcategory_status_id' => [
        'integer'  => __('validations.api.admin.csau.suggestion.category.update.subcategory_status_id.integer'),
        'between'  => __('validations.api.admin.csau.suggestion.category.update.subcategory_status_id.between'),
        'declined' => __('validations.api.admin.csau.suggestion.category.update.subcategory_status_id.declined')
    ],
    'activity_suggestion' => [
        'array' => __('validations.api.admin.csau.suggestion.category.update.activity_suggestion.array')
    ],
    'activity_status_id' => [
        'integer'  => __('validations.api.admin.csau.suggestion.category.update.activity_status_id.integer'),
        'between'  => __('validations.api.admin.csau.suggestion.category.update.activity_status_id.between'),
        'declined' => __('validations.api.admin.csau.suggestion.category.update.activity_status_id.declined')
    ],
    'unit_suggestion' => [
        'array' => __('validations.api.admin.csau.suggestion.category.update.unit_suggestion.array')
    ],
    'unit_status_id' => [
        'integer' => __('validations.api.admin.csau.suggestion.category.update.unit_status_id.integer'),
        'between' => __('validations.api.admin.csau.suggestion.category.update.unit_status_id.between')
    ],
    'unit_duration' => [
        'integer' => __('validations.api.admin.csau.suggestion.category.update.unit_duration.integer')
    ],
    'visible' => [
        'required' => __('validations.api.admin.csau.suggestion.category.update.visible.required'),
        'boolean'  => __('validations.api.admin.csau.suggestion.category.update.visible.boolean')
    ],
    'result' => [
        'error' => [
            'find'          => __('validations.api.admin.csau.suggestion.category.update.result.error.find'),
            'processed'     => __('validations.api.admin.csau.suggestion.category.update.result.error.processed'),
            'unit_duration' => __('validations.api.admin.csau.suggestion.category.update.result.error.unit_duration'),
            'category'  => [
                'create' => __('validations.api.admin.csau.suggestion.category.update.result.error.category.create')
            ],
            'subcategory'  => [
                'create' => __('validations.api.admin.csau.suggestion.category.update.result.error.subcategory.create')
            ],
            'activity'  => [
                'create' => __('validations.api.admin.csau.suggestion.category.update.result.error.activity.create')
            ],
            'unit'  => [
                'create' => __('validations.api.admin.csau.suggestion.category.update.result.error.unit.create')
            ]
        ],
        'success' => __('validations.api.admin.csau.suggestion.category.update.result.success')
    ]
];
