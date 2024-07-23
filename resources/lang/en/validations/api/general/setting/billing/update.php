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

    'first_name' => [
        'string' => __('validations.api.general.setting.billing.update.first_name.string')
    ],
    'last_name' => [
        'string' => __('validations.api.general.setting.billing.update.last_name.string')
    ],
    'country_place_id' => [
        'required' => __('validations.api.general.setting.billing.update.country_place_id.required'),
        'string'   => __('validations.api.general.setting.billing.update.country_place_id.string'),
        'exists'   => __('validations.api.general.setting.billing.update.country_place_id.exists')
    ],
    'region_place_id' => [
        'string' => __('validations.api.general.setting.billing.update.region_place_id.string'),
        'exists' => __('validations.api.general.setting.billing.update.region_place_id.exists')
    ],
    'city' => [
        'string' => __('validations.api.general.setting.billing.update.city.string')
    ],
    'postal_code' => [
        'string' => __('validations.api.general.setting.billing.update.postal_code.string')
    ],
    'address' => [
        'string' => __('validations.api.general.setting.billing.update.address.string')
    ],
    'phone_code_id' => [
        'string'   => __('validations.api.general.setting.billing.update.phone_code_id.string'),
        'exists'   => __('validations.api.general.setting.billing.update.phone_code_id.exists')
    ],
    'phone' => [
        'string' => __('validations.api.general.setting.billing.update.phone.string')
    ],
    'business_info' => [
        'required' => __('validations.api.general.setting.billing.update.business_info.required'),
        'boolean'  => __('validations.api.general.setting.billing.update.business_info.boolean')
    ],
    'company_name' => [
        'string' => __('validations.api.general.setting.billing.update.company_name.string')
    ],
    'vat_number' => [
        'string' => __('validations.api.general.setting.billing.update.vat_number.string')
    ],
    'result' => [
        'success' => __('validations.api.general.setting.billing.update.result.success')
    ]
];