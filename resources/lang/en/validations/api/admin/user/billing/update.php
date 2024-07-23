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

    'first_name' => [
        'string' => __('validations.api.admin.user.billing.update.first_name.string')
    ],
    'last_name' => [
        'string' => __('validations.api.admin.user.billing.update.last_name.string')
    ],
    'country_id' => [
        'required' => __('validations.api.admin.user.billing.update.country_id.required'),
        'string'   => __('validations.api.admin.user.billing.update.country_id.string'),
        'exists'   => __('validations.api.admin.user.billing.update.country_id.exists')
    ],
    'region_id' => [
        'string' => __('validations.api.admin.user.billing.update.region_id.string'),
        'exists' => __('validations.api.admin.user.billing.update.region_id.exists')
    ],
    'city' => [
        'string' => __('validations.api.admin.user.billing.update.city.string')
    ],
    'postal_code' => [
        'string' => __('validations.api.admin.user.billing.update.postal_code.string')
    ],
    'address' => [
        'string' => __('validations.api.admin.user.billing.update.address.string')
    ],
    'phone_code_id' => [
        'integer' => __('validations.api.admin.user.billing.update.phone_code_id.integer'),
        'exists'  => __('validations.api.admin.user.billing.update.phone_code_id.exists')
    ],
    'phone' => [
        'string' => __('validations.api.admin.user.billing.update.phone.string')
    ],
    'company_name' => [
        'string' => __('validations.api.admin.user.billing.update.company_name.string')
    ],
    'vat_number' => [
        'string' => __('validations.api.admin.user.billing.update.vat_number.string')
    ],
    'vat_number_proof_files' => [
        'array' => __('validations.api.admin.user.billing.update.vat_number_proof_files.array'),
        '*' => [
            'content' => [
                'string' => __('validations.api.admin.user.billing.update.vat_number_proof_files.*.content.string')
            ],
            'original_name' => [
                'string' => __('validations.api.admin.user.billing.update.vat_number_proof_files.*.original_name.string')
            ],
            'extension' => [
                'string' => __('validations.api.admin.user.billing.update.vat_number_proof_files.*.extension.string')
            ],
            'mime' => [
                'string' => __('validations.api.admin.user.billing.update.vat_number_proof_files.*.mime.string')
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.user.billing.update.result.error.find'),
            'vatNumberProof' => [
                'create' => __('validations.api.admin.user.billing.update.result.error.vatNumberProof.create')
            ]
        ],
        'success' => __('validations.api.admin.user.billing.update.result.success')
    ]
];