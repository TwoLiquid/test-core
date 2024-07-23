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

    'fields' => [
        'required' => __('validations.api.general.setting.payout.method.update.fields.required'),
        'array'    => __('validations.api.general.setting.payout.method.update.fields.array'),
        '*' => [
            'id' => [
                'required' => __('validations.api.general.setting.payout.method.update.fields.*.id.required'),
                'integer'  => __('validations.api.general.setting.payout.method.update.fields.*.id.integer'),
                'exists'   => __('validations.api.general.setting.payout.method.update.fields.*.id.exists')
            ],
            'value' => [
                'required' => __('validations.api.general.setting.payout.method.update.fields.*.value.required')
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find'    => __('validations.api.general.setting.payout.method.update.result.error.find'),
            'status'  => __('validations.api.general.setting.payout.method.update.result.error.status'),
            'exists'  => __('validations.api.general.setting.payout.method.update.result.error.exists'),
            'pending' => __('validations.api.general.setting.payout.method.update.result.error.pending'),
            'create'  => __('validations.api.general.setting.payout.method.update.result.error.create')
        ],
        'success' => __('validations.api.general.setting.payout.method.update.result.success')
    ]
];