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

    'activity_id' => [
        'required' => __('validations.api.admin.csau.category.unit.updatePositions.activity_id.required'),
        'integer'  => __('validations.api.admin.csau.category.unit.updatePositions.activity_id.integer'),
        'exists'   => __('validations.api.admin.csau.category.unit.updatePositions.activity_id.exists')
    ],
    'units_items' => [
        'required' => __('validations.api.admin.csau.category.unit.updatePositions.units_items.required'),
        'array'    => __('validations.api.admin.csau.category.unit.updatePositions.units_items.array'),
        '*' => [
            'id' => [
                'required' => __('validations.api.admin.csau.category.unit.updatePositions.units_items.*.id.required'),
                'integer'  => __('validations.api.admin.csau.category.unit.updatePositions.units_items.*.id.integer'),
                'exists'   => __('validations.api.admin.csau.category.unit.updatePositions.units_items.*.id.exists')
            ],
            'position' => [
                'required' => __('validations.api.admin.csau.category.unit.updatePositions.units_items.*.position.required'),
                'integer'  => __('validations.api.admin.csau.category.unit.updatePositions.units_items.*.position.integer'),
                'exists'   => __('validations.api.admin.csau.category.unit.updatePositions.units_items.*.position.exists')
            ]
        ],
    ],
    'result' => [
        'error' => [
            'find'  => __('validations.api.admin.csau.category.unit.updatePositions.result.error.find'),
            'units' => __('validations.api.admin.csau.category.unit.updatePositions.result.error.units')
        ],
        'success' => __('validations.api.admin.csau.category.unit.updatePositions.result.success')
    ]
];