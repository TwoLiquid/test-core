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

    'request_id' => [
        'string' => __('validations.api.admin.request.finance.withdrawalRequest.index.request_id.string')
    ],
    'user_id' => [
        'integer' => __('validations.api.admin.request.finance.withdrawalRequest.index.user_id.integer'),
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.request.finance.withdrawalRequest.index.date_from.string'),
        'date_format' => __('validations.api.admin.request.finance.withdrawalRequest.index.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.request.finance.withdrawalRequest.index.date_from.string'),
        'date_format' => __('validations.api.admin.request.finance.withdrawalRequest.index.date_to.date_format')
    ],
    'username' => [
        'string' => __('validations.api.admin.request.finance.withdrawalRequest.index.username.string')
    ],
    'languages_ids' => [
        'array' => __('validations.api.admin.request.finance.withdrawalRequest.index.languages_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.request.finance.withdrawalRequest.index.languages_ids.*.required'),
            'integer'  => __('validations.api.admin.request.finance.withdrawalRequest.index.languages_ids.*.integer')
        ]
    ],
    'payout_method_id' => [
        'integer' => __('validations.api.admin.request.finance.withdrawalRequest.index.payout_method_id.integer'),
    ],
    'amount' => [
        'integer' => __('validations.api.admin.request.finance.withdrawalRequest.index.amount.integer')
    ],
    'request_statuses_ids' => [
        'array' => __('validations.api.admin.request.finance.withdrawalRequest.index.request_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.request.finance.withdrawalRequest.index.request_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.request.finance.withdrawalRequest.index.request_statuses_ids.*.between')
        ]
    ],
    'receipt_statuses_ids' => [
        'array' => __('validations.api.admin.request.finance.withdrawalRequest.index.receipt_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.request.finance.withdrawalRequest.index.receipt_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.request.finance.withdrawalRequest.index.receipt_statuses_ids.*.between')
        ]
    ],
    'admin' => [
        'string' => __('validations.api.admin.request.finance.withdrawalRequest.index.admin.string')
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.request.finance.withdrawalRequest.index.sort_by.string'),
        'in'     => __('validations.api.admin.request.finance.withdrawalRequest.index.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.request.finance.withdrawalRequest.index.sort_order.string'),
        'in'     => __('validations.api.admin.request.finance.withdrawalRequest.index.sort_order.in')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.request.finance.withdrawalRequest.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.request.finance.withdrawalRequest.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.request.finance.withdrawalRequest.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.request.finance.withdrawalRequest.index.result.success')
    ]
];