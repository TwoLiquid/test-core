<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Admin Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'tax_rate' => [
        'required' => __('validations.api.admin.general.taxRule.country.update.tax_rate.required'),
        'numeric'  => __('validations.api.admin.general.taxRule.country.update.tax_rate.numeric')
    ],
    'from_date' => [
        'required'    => __('validations.api.admin.general.taxRule.country.update.from_date.required'),
        'numeric'     => __('validations.api.admin.general.taxRule.country.update.from_date.numeric'),
        'date_format' => __('validations.api.admin.general.taxRule.country.update.from_date.date_format')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.general.taxRule.country.update.result.error.find')
        ],
        'success' => __('validations.api.admin.general.taxRule.country.update.result.success')
    ]
];