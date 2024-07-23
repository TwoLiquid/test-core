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
        'integer' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.receipt_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.receipt_id.exists')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.date_from.string'),
        'date_format' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.date_from.string'),
        'date_format' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.date_to.date_format')
    ],
    'method_id' => [
        'integer' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.method_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.method_id.exists')
    ],
    'statuses_ids' => [
        'array' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.statuses_ids.*.integer'),
            'between' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.sort_by.string'),
        'in'     => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.sort_order.string'),
        'in'     => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.sort_order.in')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.index.result.success')
    ]
];