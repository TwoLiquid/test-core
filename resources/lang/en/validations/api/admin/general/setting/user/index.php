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

    'category' => [
        'required' => __('validations.api.admin.general.setting.user.index.category.required'),
        'string'   => __('validations.api.admin.general.setting.user.index.category.string'),
        'in'       => __('validations.api.admin.general.setting.user.index.category.in')
    ],
    'result' => [
        'success' => __('validations.api.admin.general.setting.user.index.result.success')
    ]
];