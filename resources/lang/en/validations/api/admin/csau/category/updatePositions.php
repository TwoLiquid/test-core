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

    'categories_items' => [
        'required' => __('validations.api.admin.csau.category.updatePositions.categories_items.required'),
        'array'    => __('validations.api.admin.csau.category.updatePositions.categories_items.array'),
        '*' => [
            'id' => [
                'required' => __('validations.api.admin.csau.category.updatePositions.categories_items.*.id.required'),
                'integer'  => __('validations.api.admin.csau.category.updatePositions.categories_items.*.id.integer'),
                'exists'   => __('validations.api.admin.csau.category.updatePositions.categories_items.*.id.exists')
            ],
            'position' => [
                'required' => __('validations.api.admin.csau.category.updatePositions.categories_items.*.position.required'),
                'integer'  => __('validations.api.admin.csau.category.updatePositions.categories_items.*.position.integer'),
                'exists'   => __('validations.api.admin.csau.category.updatePositions.categories_items.*.position.exists')
            ]
        ],
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.csau.category.updatePositions.result.error.find')
        ],
        'success' => __('validations.api.admin.csau.category.updatePositions.result.success')
    ]
];