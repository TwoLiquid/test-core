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

    'name' => [
        'array' => __('validations.api.admin.csau.activityTag.update.name.array')
    ],
    'visible_in_category' => [
        'boolean' => __('validations.api.admin.csau.activityTag.update.visible_in_category.boolean')
    ],
    'visible_in_subcategory' => [
        'boolean' => __('validations.api.admin.csau.activityTag.update.visible_in_subcategory.boolean')
    ],
    'result' => [
        'error' => [
            'activities' => __('validations.api.admin.csau.activityTag.update.result.error.activities'),
            'find'       => __('validations.api.admin.csau.activityTag.update.result.error.find')
        ],
        'success' => __('validations.api.admin.csau.activityTag.update.result.success')
    ]
];