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

    'id' => [
        'required' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.id.required'),
        'integer'  => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.id.integer'),
        'exists'   => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.id.exists')
    ],
    'type' => [
        'required' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.type.required'),
        'string'   => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.type.string'),
        'in'       => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.type.in')
    ],
    'receipt_id' => [
        'integer' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.receipt_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.receipt_id.exists')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.date_from.string'),
        'date_format' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.date_from.string'),
        'date_format' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.date_to.date_format')
    ],
    'method_id' => [
        'integer' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.method_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.method_id.exists')
    ],
    'statuses_ids' => [
        'array' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.statuses_ids.*.integer'),
            'between' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.sort_by.string'),
        'in'     => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.sort_order.string'),
        'in'     => __('validations.api.admin.user.finance.buyer.addFunds.receipt.export.sort_order.in')
    ]
];