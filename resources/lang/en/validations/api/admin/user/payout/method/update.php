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
        'required' => __('validations.api.admin.user.payout.method.update.method_id.required'),
        'integer'  => __('validations.api.admin.user.payout.method.update.method_id.integer'),
        'exists'   => __('validations.api.admin.user.payout.method.update.method_id.exists')
    ],
    'fields' => [
        'required' => __('validations.api.admin.user.payout.method.update.fields.required'),
        'array'    => __('validations.api.admin.user.payout.method.update.fields.array'),
        '*' => [
            'id' => [
                'required' => __('validations.api.admin.user.payout.method.update.fields.*.id.required'),
                'integer'  => __('validations.api.admin.user.payout.method.update.fields.*.id.integer'),
                'exists'   => __('validations.api.admin.user.payout.method.update.fields.*.id.exists')
            ],
            'value' => [
                'required' => __('validations.api.admin.user.payout.method.update.fields.*.value.required')
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find'  => [
                'user'          => __('validations.api.admin.user.payout.method.update.result.error.find.user'),
                'paymentMethod' => __('validations.api.admin.user.payout.method.update.result.error.find.paymentMethod')
            ],
            'status'  => __('validations.api.admin.user.payout.method.update.result.error.status'),
            'exists'  => __('validations.api.admin.user.payout.method.update.result.error.exists'),
            'pending' => __('validations.api.admin.user.payout.method.update.result.error.pending')
        ],
        'success' => __('validations.api.admin.user.payout.method.update.result.success')
    ]
];