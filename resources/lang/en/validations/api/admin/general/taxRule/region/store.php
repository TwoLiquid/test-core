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

    'tax_rule_country_id' => [
        'required' => __('validations.api.admin.general.taxRule.region.store.tax_rule_country_id.required'),
        'integer'  => __('validations.api.admin.general.taxRule.region.store.tax_rule_country_id.integer'),
        'exists'   => __('validations.api.admin.general.taxRule.region.store.tax_rule_country_id.exists'),
    ],
    'region_place_id' => [
        'required' => __('validations.api.admin.general.taxRule.region.store.region_place_id.required'),
        'string'   => __('validations.api.admin.general.taxRule.region.store.region_place_id.string'),
        'exists'   => __('validations.api.admin.general.taxRule.region.store.region_place_id.exists'),
    ],
    'region_code' => [
        'required' => __('validations.api.admin.general.taxRule.region.store.region_code.required'),
        'string'   => __('validations.api.admin.general.taxRule.region.store.region_code.string')
    ],
    'tax_rate' => [
        'required' => __('validations.api.admin.general.taxRule.region.store.tax_rate.required'),
        'numeric'  => __('validations.api.admin.general.taxRule.region.store.tax_rate.numeric')
    ],
    'from_date' => [
        'required'    => __('validations.api.admin.general.taxRule.region.store.from_date.required'),
        'numeric'     => __('validations.api.admin.general.taxRule.region.store.from_date.numeric'),
        'date_format' => __('validations.api.admin.general.taxRule.region.store.from_date.date_format')
    ],
    'result' => [
        'error' => [
            'exists' => __('validations.api.admin.general.taxRule.region.store.result.error.exists'),
            'create' => __('validations.api.admin.general.taxRule.region.store.result.error.create')
        ],
        'success' => __('validations.api.admin.general.taxRule.region.store.result.success')
    ]
];