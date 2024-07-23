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

    'method_id' => [
        'required' => __('validations.api.general.setting.payout.method.store.method_id.required'),
        'integer'  => __('validations.api.general.setting.payout.method.store.method_id.integer'),
        'exists'   => __('validations.api.general.setting.payout.method.store.method_id.exists')
    ],
    'fields' => [
        'required' => __('validations.api.general.setting.payout.method.store.fields.required'),
        'array'    => __('validations.api.general.setting.payout.method.store.fields.array'),
        '*' => [
            'id' => [
                'required' => __('validations.api.general.setting.payout.method.store.fields.*.id.required'),
                'integer'  => __('validations.api.general.setting.payout.method.store.fields.*.id.integer'),
                'exists'   => __('validations.api.general.setting.payout.method.store.fields.*.id.exists')
            ],
            'value' => [
                'required' => __('validations.api.general.setting.payout.method.store.fields.*.value.required')
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find'    => __('validations.api.general.setting.payout.method.store.result.error.find'),
            'status'  => __('validations.api.general.setting.payout.method.store.result.error.status'),
            'exists'  => __('validations.api.general.setting.payout.method.store.result.error.exists'),
            'pending' => __('validations.api.general.setting.payout.method.store.result.error.pending'),
            'create'  => __('validations.api.general.setting.payout.method.store.result.error.create')
        ],
        'success' => __('validations.api.general.setting.payout.method.store.result.success')
    ]
];