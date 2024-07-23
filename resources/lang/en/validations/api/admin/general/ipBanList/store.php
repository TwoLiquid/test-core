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

    'ip_addresses' => [
        'required' => __('validations.api.admin.general.ipBanList.store.ip_addresses.required'),
        'string'   => __('validations.api.admin.general.ipBanList.store.ip_addresses.string')
    ],
    'result' => [
        'error' => [
            'create' => __('validations.api.admin.general.ipBanList.store.result.error.create')
        ],
        'success' => __('validations.api.admin.general.ipBanList.store.result.success')
    ]
];