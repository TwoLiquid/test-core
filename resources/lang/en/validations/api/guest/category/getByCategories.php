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

    'categories_ids' => [
        'required' => __('validations.api.guest.category.getByCategories.categories_ids.required'),
        'array'    => __('validations.api.guest.category.getByCategories.categories_ids.array'),
        '*' => [
            'integer' => __('validations.api.guest.category.getByCategories.categories_ids.*.integer'),
            'exists'  => __('validations.api.guest.category.getByCategories.categories_ids.*.exists')
        ]
    ],
    'name' => [
        'string' => __('validations.api.guest.category.getByCategories.name.string')
    ],
    'result' => [
        'success' => __('validations.api.guest.category.getByCategories.result.success')
    ]
];