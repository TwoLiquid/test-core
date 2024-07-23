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
        'required' => __('validations.api.admin.invoice.addFunds.receipt.addPayment.payment_date.required'),
        'integer'  => __('validations.api.admin.invoice.addFunds.receipt.addPayment.payment_date.integer')
    ],
    'method_id' => [
        'required' => __('validations.api.admin.invoice.addFunds.receipt.addPayment.method_id.required'),
        'integer'  => __('validations.api.admin.invoice.addFunds.receipt.addPayment.method_id.integer'),
        'exists'   => __('validations.api.admin.invoice.addFunds.receipt.addPayment.method_id.exists')
    ],
    'external_id' => [
        'string' => __('validations.api.admin.invoice.addFunds.receipt.addPayment.external_id.string')
    ],
    'amount' => [
        'required' => __('validations.api.admin.invoice.addFunds.receipt.addPayment.amount.required'),
        'numeric'  => __('validations.api.admin.invoice.addFunds.receipt.addPayment.amount.numeric')
    ],
    'transaction_fee' => [
        'numeric' => __('validations.api.admin.invoice.addFunds.receipt.addPayment.transaction_fee.numeric')
    ],
    'result' => [
        'error' => [
            'find'   => __('validations.api.admin.invoice.addFunds.receipt.addPayment.result.error.find'),
            'status' => __('validations.api.admin.invoice.addFunds.receipt.addPayment.result.error.status'),
            'amount' => __('validations.api.admin.invoice.addFunds.receipt.addPayment.result.error.amount')
        ],
        'success' => __('validations.api.admin.invoice.addFunds.receipt.addPayment.result.success')
    ]
];