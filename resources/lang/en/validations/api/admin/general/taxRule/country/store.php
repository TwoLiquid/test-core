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

    'country_place_id' => [
        'required' => __('validations.api.admin.general.taxRule.country.store.country_place_id.required'),
        'string'   => __('validations.api.admin.general.taxRule.country.store.country_place_id.string'),
        'exists'   => __('validations.api.admin.general.taxRule.country.store.country_place_id.exists'),
    ],
    'tax_rate' => [
        'required' => __('validations.api.admin.general.taxRule.country.store.tax_rate.required'),
        'numeric'  => __('validations.api.admin.general.taxRule.country.store.tax_rate.numeric')
    ],
    'from_date' => [
        'required'    => __('validations.api.admin.general.taxRule.country.store.from_date.required'),
        'numeric'     => __('validations.api.admin.general.taxRule.country.store.from_date.numeric'),
        'date_format' => __('validations.api.admin.general.taxRule.country.store.from_date.date_format')
    ],
    'result' => [
        'error' => [
            'exists' => __('validations.api.admin.general.taxRule.country.store.result.error.exists'),
            'create' => __('validations.api.admin.general.taxRule.country.store.result.error.create')
        ],
        'success' => __('validations.api.admin.general.taxRule.country.store.result.success')
    ]
];