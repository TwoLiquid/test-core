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

    'vat_number_proof_files' => [
        'array' => __('validations.api.admin.user.billing.vatNumberProof.uploadFiles.vat_number_proof_files.array'),
        '*' => [
            'content' => [
                'string' => __('validations.api.admin.user.billing.vatNumberProof.uploadFiles.vat_number_proof_files.*.content.string')
            ],
            'original_name' => [
                'string' => __('validations.api.admin.user.billing.vatNumberProof.uploadFiles.vat_number_proof_files.*.original_name.string')
            ],
            'extension' => [
                'string' => __('validations.api.admin.user.billing.vatNumberProof.uploadFiles.vat_number_proof_files.*.extension.string')
            ],
            'mime' => [
                'string' => __('validations.api.admin.user.billing.vatNumberProof.uploadFiles.vat_number_proof_files.*.mime.string')
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find' => [
                'billingSetting' => __('validations.api.admin.user.billing.vatNumberProof.uploadFiles.result.error.find.billingSetting'),
                'vatNumberProof' => __('validations.api.admin.user.billing.vatNumberProof.uploadFiles.result.error.find.vatNumberProof')
            ]
        ],
        'success' => __('validations.api.admin.user.billing.vatNumberProof.uploadFiles.result.success')
    ]
];