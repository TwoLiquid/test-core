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

    'receipt_id' => [
        'integer' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.receipt_id.integer')
    ],
    'date_from' => [
        'date_format' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.date_from.date_format')
    ],
    'date_to' => [
        'date_format' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.date_to.date_format')
    ],
    'amount' => [
        'integer' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.amount.integer')
    ],
    'payment_fee' => [
        'integer' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.payment_fee.integer')
    ],
    'total' => [
        'integer' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.total.integer')
    ],
    'payment_methods_ids' => [
        'array' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.payment_methods_ids.array'),
        '*' => [
            'required' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.payment_methods_ids.*.required'),
            'integer'  => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.payment_methods_ids.*.integer'),
            'exists'   => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.payment_methods_ids.*.exists')
        ]
    ],
    'add_funds_receipt_statuses_ids' => [
        'array' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.add_funds_receipt_statuses_ids.array'),
        '*' => [
            'required' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.add_funds_receipt_statuses_ids.*.required'),
            'integer'  => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.add_funds_receipt_statuses_ids.*.integer'),
            'between'  => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.add_funds_receipt_statuses_ids.*.between')
        ]
    ],
    'page' => [
        'integer' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.page.integer')
    ],
    'per_page' => [
        'integer' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.per_page.integer')
    ],
    'result' => [
        'success' => __('validations.api.general.dashboard.finance.buyer.addFunds.receipt.index.result.success')
    ]
];