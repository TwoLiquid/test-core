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
        'integer' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.item_id.integer')
    ],
    'invoice_id' => [
        'integer' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.invoice_id.integer')
    ],
    'username' => [
        'string' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.username.string')
    ],
    'date_from' => [
        'date_format' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.date_from.date_format')
    ],
    'date_to' => [
        'date_format' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.date_to.date_format')
    ],
    'amount' => [
        'integer' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.amount.integer')
    ],
    'vybe_types_ids' => [
        'array' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.vybe_types_ids.array'),
        '*' => [
            'required' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.vybe_types_ids.*.required'),
            'integer'  => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.vybe_types_ids.*.integer'),
            'between'  => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.vybe_types_ids.*.between')
        ]
    ],
    'order_item_statuses_ids' => [
        'array' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.order_item_statuses_ids.array'),
        '*' => [
            'required' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.order_item_statuses_ids.*.required'),
            'integer'  => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.order_item_statuses_ids.*.integer'),
            'between'  => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.order_item_statuses_ids.*.between')
        ]
    ],
    'invoice_statuses_ids' => [
        'array' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.invoice_statuses_ids.array'),
        '*' => [
            'required' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.invoice_statuses_ids.*.required'),
            'integer'  => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.invoice_statuses_ids.*.integer'),
            'between'  => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.invoice_statuses_ids.*.between')
        ]
    ],
    'page' => [
        'integer' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.page.integer')
    ],
    'per_page' => [
        'integer' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.per_page.integer')
    ],
    'result' => [
        'success' => __('validations.api.general.dashboard.finance.seller.invoice.tip.index.result.success')
    ]
];