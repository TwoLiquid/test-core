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
        'array' => __('validations.api.guest.activity.getByCategories.categories_ids.array'),
        '*' => [
            'integer' => __('validations.api.guest.activity.getByCategories.categories_ids.*.integer'),
            'exists'  => __('validations.api.guest.activity.getByCategories.categories_ids.*.exists')
        ]
    ],
    'result' => [
        'success' => __('validations.api.guest.activity.getByCategories.result.success')
    ]
];