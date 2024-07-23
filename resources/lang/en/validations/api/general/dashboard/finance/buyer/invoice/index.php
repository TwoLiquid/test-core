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

    'invoice_id' => [
        'integer' => __('validations.api.general.dashboard.finance.buyer.invoice.index.invoice_id.integer')
    ],
    'date_from' => [
        'date_format' => __('validations.api.general.dashboard.finance.buyer.invoice.index.date_from.date_format')
    ],
    'date_to' => [
        'date_format' => __('validations.api.general.dashboard.finance.buyer.invoice.index.date_to.date_format')
    ],
    'username' => [
        'string' => __('validations.api.general.dashboard.finance.buyer.invoice.index.username.string')
    ],
    'total' => [
        'integer' => __('validations.api.general.dashboard.finance.buyer.invoice.index.total.integer')
    ],
    'vybe_types_ids' => [
        'array' => __('validations.api.general.dashboard.finance.buyer.invoice.index.vybe_types_ids.array'),
        '*' => [
            'required' => __('validations.api.general.dashboard.finance.buyer.invoice.index.vybe_types_ids.*.required'),
            'integer'  => __('validations.api.general.dashboard.finance.buyer.invoice.index.vybe_types_ids.*.integer'),
            'between'  => __('validations.api.general.dashboard.finance.buyer.invoice.index.vybe_types_ids.*.between')
        ]
    ],
    'order_item_payment_statuses_ids' => [
        'array' => __('validations.api.general.dashboard.finance.buyer.invoice.index.order_item_payment_statuses_ids.array'),
        '*' => [
            'required' => __('validations.api.general.dashboard.finance.buyer.invoice.index.order_item_payment_statuses_ids.*.required'),
            'integer'  => __('validations.api.general.dashboard.finance.buyer.invoice.index.order_item_payment_statuses_ids.*.integer'),
            'between'  => __('validations.api.general.dashboard.finance.buyer.invoice.index.order_item_payment_statuses_ids.*.between')
        ]
    ],
    'invoice_statuses_ids' => [
        'array' => __('validations.api.general.dashboard.finance.buyer.invoice.index.invoice_statuses_ids.array'),
        '*' => [
            'required' => __('validations.api.general.dashboard.finance.buyer.invoice.index.invoice_statuses_ids.*.required'),
            'integer'  => __('validations.api.general.dashboard.finance.buyer.invoice.index.invoice_statuses_ids.*.integer'),
            'between'  => __('validations.api.general.dashboard.finance.buyer.invoice.index.invoice_statuses_ids.*.between')
        ]
    ],
    'page' => [
        'integer' => __('validations.api.general.dashboard.finance.buyer.invoice.index.page.integer')
    ],
    'per_page' => [
        'integer' => __('validations.api.general.dashboard.finance.buyer.invoice.index.per_page.integer')
    ],
    'result' => [
        'success' => __('validations.api.general.dashboard.finance.buyer.invoice.index.result.success')
    ]
];