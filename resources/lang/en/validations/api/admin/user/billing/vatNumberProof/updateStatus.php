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

    'vat_number_proof_status_id' => [
        'required' => __('validations.api.admin.user.billing.vatNumberProof.updateStatus.vat_number_proof_status_id.required'),
        'integer'  => __('validations.api.admin.user.billing.vatNumberProof.updateStatus.vat_number_proof_status_id.integer'),
        'in'       => __('validations.api.admin.user.billing.vatNumberProof.updateStatus.vat_number_proof_status_id.in')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.user.billing.vatNumberProof.updateStatus.result.error.find')
        ],
        'success' => __('validations.api.admin.user.billing.vatNumberProof.updateStatus.result.success')
    ]
];