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
        'required' => __('validations.api.general.tip.index.item_id.required'),
        'integer'  => __('validations.api.general.tip.index.item_id.integer'),
        'exists'   => __('validations.api.general.tip.index.item_id.exists')
    ],
    'method_id' => [
        'integer' => __('validations.api.general.tip.index.method_id.integer'),
        'exists'  => __('validations.api.general.tip.index.method_id.exists')
    ],
    'amount' => [
        'integer' => __('validations.api.general.tip.index.amount.integer')
    ],
    'result' => [
        'error' => [
            'orderItem' => [
                'finished' => __('validations.api.general.tip.index.result.error.orderItem.finished')
            ],
            'vybeOwner' => __('validations.api.general.tip.index.result.error.vybeOwner'),
            'exists'    => __('validations.api.general.tip.index.result.error.exists')
        ],
        'success' => __('validations.api.general.tip.index.result.success')
    ]
];