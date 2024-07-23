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

    'exclude_tax' => [
        'required' => __('validations.api.admin.user.billing.vatNumberProof.updateExcludeTax.exclude_tax.required'),
        'boolean'  => __('validations.api.admin.user.billing.vatNumberProof.updateExcludeTax.exclude_tax.boolean')
    ],
    'result' => [
        'error' => [
            'find'   => __('validations.api.admin.user.billing.vatNumberProof.updateExcludeTax.result.error.find'),
            'status' => __('validations.api.admin.user.billing.vatNumberProof.updateExcludeTax.result.error.status'),
            'exists' => __('validations.api.admin.user.billing.vatNumberProof.updateExcludeTax.result.error.exists')
        ],
        'success' => __('validations.api.admin.user.billing.vatNumberProof.updateExcludeTax.result.success')
    ]
];