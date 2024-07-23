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

    'type' => [
        'required' => __('validations.api.admin.invoice.withdrawal.receipt.export.type.required'),
        'string'   => __('validations.api.admin.invoice.withdrawal.receipt.export.type.string'),
        'in'       => __('validations.api.admin.invoice.withdrawal.receipt.export.type.in')
    ],
    'receipt_id' => [
        'integer' => __('validations.api.admin.invoice.withdrawal.receipt.export.receipt_id.integer'),
        'exists'  => __('validations.api.admin.invoice.withdrawal.receipt.export.receipt_id.exists')
    ],
    'request_id' => [
        'string' => __('validations.api.admin.invoice.withdrawal.receipt.export.request_id.string'),
        'exists' => __('validations.api.admin.invoice.withdrawal.receipt.export.request_id.exists')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.invoice.withdrawal.receipt.export.date_from.string'),
        'date_format' => __('validations.api.admin.invoice.withdrawal.receipt.export.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.invoice.withdrawal.receipt.export.date_from.string'),
        'date_format' => __('validations.api.admin.invoice.withdrawal.receipt.export.date_to.date_format')
    ],
    'client' => [
        'string' => __('validations.api.admin.invoice.withdrawal.receipt.export.client.string')
    ],
    'payout_method_id' => [
        'integer' => __('validations.api.admin.invoice.withdrawal.receipt.export.payout_method_id.integer'),
        'exists'  => __('validations.api.admin.invoice.withdrawal.receipt.export.payout_method_id.exists')
    ],
    'receipt_statuses_ids' => [
        'array' => __('validations.api.admin.invoice.withdrawal.receipt.export.receipt_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.invoice.withdrawal.receipt.export.receipt_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.invoice.withdrawal.receipt.export.receipt_statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.invoice.withdrawal.receipt.export.sort_by.string'),
        'in'     => __('validations.api.admin.invoice.withdrawal.receipt.export.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.invoice.withdrawal.receipt.export.sort_order.string'),
        'in'     => __('validations.api.admin.invoice.withdrawal.receipt.export.sort_order.in')
    ]
];