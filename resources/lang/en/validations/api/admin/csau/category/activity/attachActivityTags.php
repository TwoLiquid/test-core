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

    'activities_ids' => [
        'array' => __('validations.api.admin.csau.category.activity.attachActivityTags.activities_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.csau.category.activity.attachActivityTags.activities_ids.*.required'),
            'integer'  => __('validations.api.admin.csau.category.activity.attachActivityTags.activities_ids.*.integer'),
            'exists'   => __('validations.api.admin.csau.category.activity.attachActivityTags.activities_ids.*.exists')
        ]
    ],
    'activity_tags_ids' => [
        'array' => __('validations.api.admin.csau.category.activity.attachActivityTags.activity_tags_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.csau.category.activity.attachActivityTags.activity_tags_ids.*.required'),
            'integer'  => __('validations.api.admin.csau.category.activity.attachActivityTags.activity_tags_ids.*.integer'),
            'exists'   => __('validations.api.admin.csau.category.activity.attachActivityTags.activity_tags_ids.*.exists')
        ]
    ],
    'result' => [
        'success' => __('validations.api.admin.csau.category.activity.attachActivityTags.result.success')
    ]
];