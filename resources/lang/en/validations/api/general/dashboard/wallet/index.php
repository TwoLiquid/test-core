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

    'balance_type_id' => [
        'required' => __('validations.api.general.dashboard.wallet.index.balance_type_id.required'),
        'integer'  => __('validations.api.general.dashboard.wallet.index.balance_type_id.integer'),
        'between'  => __('validations.api.general.dashboard.wallet.index.balance_type_id.between')
    ],
    'page' => [
        'integer' => __('validations.api.general.dashboard.wallet.index.page.integer')
    ],
    'per_page' => [
        'integer' => __('validations.api.general.dashboard.wallet.index.per_page.integer')
    ],
    'search' => [
        'string' => __('validations.api.general.dashboard.wallet.index.search.string')
    ],
    'date_from' => [
        'string'      => __('validations.api.general.dashboard.wallet.index.date_from.string'),
        'date_format' => __('validations.api.general.dashboard.wallet.index.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.general.dashboard.wallet.index.date_to.string'),
        'date_format' => __('validations.api.general.dashboard.wallet.index.date_to.date_format')
    ],
    'result' => [
        'success' => __('validations.api.general.dashboard.wallet.index.result.success')
    ]
];