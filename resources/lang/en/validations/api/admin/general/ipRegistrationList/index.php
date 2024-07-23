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

    'registration_date_from' => [
        'string'      => __('validations.api.admin.general.ipRegistrationList.index.registration_date_from.string'),
        'date_format' => __('validations.api.admin.general.ipRegistrationList.index.registration_date_from.date_format')
    ],
    'registration_date_to' => [
        'string'      => __('validations.api.admin.general.ipRegistrationList.index.registration_date_to.string'),
        'date_format' => __('validations.api.admin.general.ipRegistrationList.index.registration_date_to.date_format')
    ],
    'ip_address' => [
        'string' => __('validations.api.admin.general.ipRegistrationList.index.ip_address.string'),
        'ip'     => __('validations.api.admin.general.ipRegistrationList.index.ip_address.ip')
    ],
    'username' => [
        'string' => __('validations.api.admin.general.ipRegistrationList.index.username.string')
    ],
    'name' => [
        'string' => __('validations.api.admin.general.ipRegistrationList.index.name.string')
    ],
    'status_id' => [
        'integer' => __('validations.api.admin.general.ipRegistrationList.index.status_id.integer')
    ],
    'location' => [
        'string' => __('validations.api.admin.general.ipRegistrationList.index.location.string')
    ],
    'vpn' => [
        'boolean' => __('validations.api.admin.general.ipRegistrationList.index.vpn.boolean')
    ],
    'duplicates' => [
        'string' => __('validations.api.admin.general.ipRegistrationList.index.duplicates.string')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.general.ipRegistrationList.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.general.ipRegistrationList.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.general.ipRegistrationList.index.result.success')
    ]
];