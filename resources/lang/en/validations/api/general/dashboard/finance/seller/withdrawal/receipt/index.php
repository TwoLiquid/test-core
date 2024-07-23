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

    'request_id' => [
        'integer' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.request_id.integer')
    ],
    'receipt_id' => [
        'integer' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.request_id.integer')
    ],
    'request_date_from' => [
        'date_format' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.request_date_from.date_format')
    ],
    'request_date_to' => [
        'date_format' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.request_date_to.date_format')
    ],
    'receipt_date_from' => [
        'date_format' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.receipt_date_from.date_format')
    ],
    'receipt_date_to' => [
        'date_format' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.receipt_date_to.date_format')
    ],
    'amount' => [
        'integer' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.amount.integer')
    ],
    'payment_methods_ids' => [
        'array' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.payment_methods_ids.array'),
        '*' => [
            'required' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.payment_methods_ids.*.required'),
            'integer'  => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.payment_methods_ids.*.integer'),
            'between'  => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.payment_methods_ids.*.between')
        ]
    ],
    'request_statuses_ids' => [
        'array' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.request_statuses_ids.array'),
        '*' => [
            'required' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.request_statuses_ids.*.required'),
            'integer'  => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.request_statuses_ids.*.integer'),
            'between'  => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.request_statuses_ids.*.between')
        ]
    ],
    'receipt_statuses_ids' => [
        'array' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.receipt_statuses_ids.array'),
        '*' => [
            'required' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.receipt_statuses_ids.*.required'),
            'integer'  => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.receipt_statuses_ids.*.integer'),
            'between'  => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.receipt_statuses_ids.*.between')
        ]
    ],
    'page' => [
        'integer' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.page.integer')
    ],
    'per_page' => [
        'integer' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.per_page.integer')
    ],
    'result' => [
        'success' => __('validations.api.general.dashboard.finance.seller.withdrawal.receipt.index.result.success')
    ]
];