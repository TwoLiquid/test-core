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

    'vybe_title' => [
        'string' => __('validations.api.general.dashboard.purchase.index.vybe_title.string')
    ],
    'username' => [
        'string' => __('validations.api.general.dashboard.purchase.index.username.string')
    ],
    'appearance_id' => [
        'integer' => __('validations.api.general.dashboard.purchase.index.appearance_id.integer'),
        'between' => __('validations.api.general.dashboard.purchase.index.appearance_id.between')
    ],
    'vybe_type_id' => [
        'integer' => __('validations.api.general.dashboard.purchase.index.vybe_type_id.integer'),
        'between' => __('validations.api.general.dashboard.purchase.index.vybe_type_id.between')
    ],
    'activity_id' => [
        'integer' => __('validations.api.general.dashboard.purchase.index.activity_id.integer'),
        'exists' => __('validations.api.general.dashboard.purchase.index.activity_id.exists')
    ],
    'start_datetime' => [
        'date_format' => __('validations.api.general.dashboard.purchase.index.start_datetime.date_format')
    ],
    'ending_datetime' => [
        'date_format' => __('validations.api.general.dashboard.purchase.index.ending_datetime.date_format')
    ],
    'earnings_from' => [
        'integer' => __('validations.api.general.dashboard.purchase.index.earnings_from.integer')
    ],
    'earnings_to' => [
        'integer' => __('validations.api.general.dashboard.purchase.index.earnings_to.integer')
    ],
    'quantity' => [
        'integer' => __('validations.api.general.dashboard.purchase.index.quantity.integer')
    ],
    'order_item_status_id' => [
        'integer' => __('validations.api.general.dashboard.purchase.index.order_item_status_id.integer'),
        'between' => __('validations.api.general.dashboard.purchase.index.order_item_status_id.between')
    ],
    'only_open' => [
        'boolean' => __('validations.api.general.dashboard.purchase.index.only_open.boolean')
    ],
    'order_item_purchase_sort_by_id' => [
        'integer' => __('validations.api.general.dashboard.purchase.index.order_item_purchase_sort_by_id.integer'),
        'between' => __('validations.api.general.dashboard.purchase.index.order_item_purchase_sort_by_id.between')
    ],
    'order_item_purchase_sort_order' => [
        'string' => __('validations.api.general.dashboard.purchase.index.order_item_purchase_sort_order.string'),
        'in'     => __('validations.api.general.dashboard.purchase.index.order_item_purchase_sort_order.in')
    ],
    'result' => [
        'success' => __('validations.api.general.dashboard.purchase.index.result.success')
    ]
];