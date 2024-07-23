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

    'activities_items' => [
        'required' => __('validations.api.admin.csau.category.activity.updatePositions.activities_items.required'),
        'array'    => __('validations.api.admin.csau.category.activity.updatePositions.activities_items.array'),
        '*' => [
            'id' => [
                'required' => __('validations.api.admin.csau.category.activity.updatePositions.activities_items.*.id.required'),
                'integer'  => __('validations.api.admin.csau.category.activity.updatePositions.activities_items.*.id.integer'),
                'exists'   => __('validations.api.admin.csau.category.activity.updatePositions.activities_items.*.id.exists')
            ],
            'position' => [
                'required' => __('validations.api.admin.csau.category.activity.updatePositions.activities_items.*.position.required'),
                'integer'  => __('validations.api.admin.csau.category.activity.updatePositions.activities_items.*.position.integer'),
                'exists'   => __('validations.api.admin.csau.category.activity.updatePositions.activities_items.*.position.exists')
            ]
        ],
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.csau.category.activity.updatePositions.result.error.find')
        ],
        'success' => __('validations.api.admin.csau.category.activity.updatePositions.result.success')
    ]
];