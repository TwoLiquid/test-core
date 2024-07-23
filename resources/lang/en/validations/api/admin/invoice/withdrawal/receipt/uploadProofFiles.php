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

    'withdrawal_receipt_proof_files' => [
        'array' => __('validations.api.admin.invoice.withdrawal.receipt.uploadProofFiles.withdrawal_receipt_proof_files.array'),
        '*' => [
            'content' => [
                'string' => __('validations.api.admin.invoice.withdrawal.receipt.uploadProofFiles.withdrawal_receipt_proof_files.*.content.string')
            ],
            'original_name' => [
                'string' => __('validations.api.admin.invoice.withdrawal.receipt.uploadProofFiles.withdrawal_receipt_proof_files.*.original_name.string')
            ],
            'extension' => [
                'string' => __('validations.api.admin.invoice.withdrawal.receipt.uploadProofFiles.withdrawal_receipt_proof_files.*.extension.string')
            ],
            'mime' => [
                'string' => __('validations.api.admin.invoice.withdrawal.receipt.uploadProofFiles.withdrawal_receipt_proof_files.*.mime.string')
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.invoice.withdrawal.receipt.uploadProofFiles.result.error.find')
        ],
        'success' => __('validations.api.admin.invoice.withdrawal.receipt.uploadProofFiles.result.success')
    ]
];