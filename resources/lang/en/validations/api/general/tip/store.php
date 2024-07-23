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

    'item_id' => [
        'required' => __('validations.api.general.tip.store.item_id.required'),
        'integer'  => __('validations.api.general.tip.store.item_id.integer'),
        'exists'   => __('validations.api.general.tip.store.item_id.exists')
    ],
    'method_id' => [
        'required' => __('validations.api.general.tip.store.method_id.required'),
        'integer'  => __('validations.api.general.tip.store.method_id.integer'),
        'exists'   => __('validations.api.general.tip.store.method_id.exists')
    ],
    'amount' => [
        'required' => __('validations.api.general.tip.store.amount.required'),
        'integer'  => __('validations.api.general.tip.store.amount.integer')
    ],
    'comment' => [
        'string' => __('validations.api.general.tip.store.comment.string')
    ],
    'result' => [
        'error' => [
            'orderItem' => [
                'finished' => __('validations.api.general.tip.store.result.error.orderItem.finished')
            ],
            'vybeOwner' => __('validations.api.general.tip.store.result.error.vybeOwner'),
            'exists'    => __('validations.api.general.tip.store.result.error.exists'),
            'balance' => [
                'enough' => __('validations.api.general.tip.store.result.error.balance.enough')
            ],
            'create' => [
                'tipTransaction' => __('validations.api.general.tip.store.result.error.create.tipTransaction')
            ]
        ],
        'success' => __('validations.api.general.tip.store.result.success')
    ]
];