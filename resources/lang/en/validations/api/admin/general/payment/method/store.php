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

    'payment_status_id' => [
        'required' => __('validations.api.admin.general.payment.method.store.payment_status_id.required'),
        'integer'  => __('validations.api.admin.general.payment.method.store.payment_status_id.integer'),
        'between'  => __('validations.api.admin.general.payment.method.store.payment_status_id.between')
    ],
    'withdrawal_status_id' => [
        'required' => __('validations.api.admin.general.payment.method.store.withdrawal_status_id.required'),
        'integer'  => __('validations.api.admin.general.payment.method.store.withdrawal_status_id.integer'),
        'between'  => __('validations.api.admin.general.payment.method.store.withdrawal_status_id.between')
    ],
    'name' => [
        'required' => __('validations.api.admin.general.payment.method.store.name.required'),
        'numeric'  => __('validations.api.admin.general.payment.method.store.name.numeric')
    ],
    'payment_fee' => [
        'required' => __('validations.api.admin.general.payment.method.store.payment_fee.required'),
        'numeric'  => __('validations.api.admin.general.payment.method.store.payment_fee.numeric')
    ],
    'order_form' => [
        'required' => __('validations.api.admin.general.payment.method.store.order_form.required'),
        'numeric'  => __('validations.api.admin.general.payment.method.store.order_form.numeric')
    ],
    'display_name' => [
        'required' => __('validations.api.admin.general.payment.method.store.display_name.required'),
        'array'    => __('validations.api.admin.general.payment.method.store.display_name.array')
    ],
    'duration_title' => [
        'required' => __('validations.api.admin.general.payment.method.store.duration_title.required'),
        'array'    => __('validations.api.admin.general.payment.method.store.duration_title.array')
    ],
    'duration_amount' => [
        'required' => __('validations.api.admin.general.payment.method.store.duration_amount.required'),
        'array'    => __('validations.api.admin.general.payment.method.store.duration_amount.array')
    ],
    'fee_title' => [
        'required' => __('validations.api.admin.general.payment.method.store.fee_title.required'),
        'array'    => __('validations.api.admin.general.payment.method.store.fee_title.array')
    ],
    'fee_amount' => [
        'required' => __('validations.api.admin.general.payment.method.store.fee_amount.required'),
        'array'    => __('validations.api.admin.general.payment.method.store.fee_amount.array')
    ],
    'country_places_ids' => [
        'required' => __('validations.api.admin.general.payment.method.store.country_places_ids.required'),
        'array'    => __('validations.api.admin.general.payment.method.store.country_places_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.general.payment.method.store.country_places_ids.*.required'),
            'integer'  => __('validations.api.admin.general.payment.method.store.country_places_ids.*.integer'),
            'exists'   => __('validations.api.admin.general.payment.method.store.country_places_ids.*.exists')
        ]
    ],
    'fields '=> [
        'required' => __('validations.api.admin.general.payment.method.store.fields .required'),
        'array'    => __('validations.api.admin.general.payment.method.store.fields .array'),
        '*' => [
            'type_id' => [
                'required' => __('validations.api.admin.general.payment.method.store.fields .*.type_id.required'),
                'integer'  => __('validations.api.admin.general.payment.method.store.fields .*.type_id.integer'),
                'between'  => __('validations.api.admin.general.payment.method.store.fields .*.type_id.between')
            ],
            'title' => [
                'required' => __('validations.api.admin.general.payment.method.store.fields .*.title.required'),
                'array'    => __('validations.api.admin.general.payment.method.store.fields .*.title.array')
            ],
            'placeholder' => [
                'required' => __('validations.api.admin.general.payment.method.store.fields .*.placeholder.required'),
                'array'    => __('validations.api.admin.general.payment.method.store.fields .*.placeholder.array')
            ],
            'tooltip' => [
                'required' => __('validations.api.admin.general.payment.method.store.fields .*.tooltip.required'),
                'array'    => __('validations.api.admin.general.payment.method.store.fields .*.tooltip.array')
            ]
        ]
    ],
    'image' => [
        'required' => __('validations.api.admin.general.payment.method.store.image.required'),
        'array'    => __('validations.api.admin.general.payment.method.store.image.array'),
        'content'  => [
            'required' => __('validations.api.admin.general.payment.method.store.image.content.required'),
            'string'   => __('validations.api.admin.general.payment.method.store.image.content.string')
        ],
        'extension' => [
            'required' => __('validations.api.admin.general.payment.method.store.image.extension.required'),
            'string'   => __('validations.api.admin.general.payment.method.store.image.extension.string')
        ],
        'mime' => [
            'required' => __('validations.api.admin.general.payment.method.store.image.mime.required'),
            'string'   => __('validations.api.admin.general.payment.method.store.image.mime.string')
        ]
    ],
    'result' => [
        'error' => [
            'create' => __('validations.api.admin.general.payment.method.store.result.error.create')
        ],
        'success' => __('validations.api.admin.general.payment.method.store.result.success')
    ]
];