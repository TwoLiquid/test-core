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

    'payment_date' => [
        'required'     => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.payment_date.required'),
        'date_format'  => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.payment_date.date_format')
    ],
    'method_id' => [
        'required' => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.method_id.required'),
        'integer'  => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.method_id.integer'),
        'exists'   => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.method_id.exists')
    ],
    'external_id' => [
        'string' => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.external_id.string')
    ],
    'amount' => [
        'required' => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.amount.required'),
        'numeric'  => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.amount.numeric')
    ],
    'transaction_fee' => [
        'numeric'  => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.transaction_fee.numeric')
    ],
    'result' => [
        'error' => [
            'find'   => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.result.error.find'),
            'status' => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.result.error.status'),
            'amount' => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.result.error.amount')
        ],
        'success' => __('validations.api.admin.invoice.withdrawal.receipt.addTransfer.result.success')
    ]
];