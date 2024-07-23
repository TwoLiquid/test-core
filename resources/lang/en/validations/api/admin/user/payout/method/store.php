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
        'required' => __('validations.api.admin.user.payout.method.store.method_id.required'),
        'integer'  => __('validations.api.admin.user.payout.method.store.method_id.integer'),
        'exists'   => __('validations.api.admin.user.payout.method.store.method_id.exists')
    ],
    'fields' => [
        'required' => __('validations.api.admin.user.payout.method.store.fields.required'),
        'array'    => __('validations.api.admin.user.payout.method.store.fields.array'),
        '*' => [
            'id' => [
                'required' => __('validations.api.admin.user.payout.method.store.fields.*.id.required'),
                'integer'  => __('validations.api.admin.user.payout.method.store.fields.*.id.integer'),
                'exists'   => __('validations.api.admin.user.payout.method.store.fields.*.id.exists')
            ],
            'value' => [
                'required' => __('validations.api.admin.user.payout.method.store.fields.*.value.required')
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find'  => [
                'user'          => __('validations.api.admin.user.payout.method.store.result.error.find.user'),
                'paymentMethod' => __('validations.api.admin.user.payout.method.store.result.error.find.paymentMethod')
            ],
            'status'  => __('validations.api.admin.user.payout.method.store.result.error.status'),
            'exists'  => __('validations.api.admin.user.payout.method.store.result.error.exists'),
            'pending' => __('validations.api.admin.user.payout.method.store.result.error.pending')
        ],
        'success' => __('validations.api.admin.user.payout.method.store.result.success')
    ]
];