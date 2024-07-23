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
        'required' => __('validations.api.admin.general.payment.method.update.payment_status_id.required'),
        'integer'  => __('validations.api.admin.general.payment.method.update.payment_status_id.integer'),
        'between'  => __('validations.api.admin.general.payment.method.update.payment_status_id.between')
    ],
    'withdrawal_status_id' => [
        'required' => __('validations.api.admin.general.payment.method.update.withdrawal_status_id.required'),
        'integer'  => __('validations.api.admin.general.payment.method.update.withdrawal_status_id.integer'),
        'between'  => __('validations.api.admin.general.payment.method.update.withdrawal_status_id.between')
    ],
    'name' => [
        'required' => __('validations.api.admin.general.payment.method.update.name.required'),
        'numeric'  => __('validations.api.admin.general.payment.method.update.name.numeric')
    ],
    'payment_fee' => [
        'required' => __('validations.api.admin.general.payment.method.update.payment_fee.required'),
        'numeric'  => __('validations.api.admin.general.payment.method.update.payment_fee.numeric')
    ],
    'order_form' => [
        'required' => __('validations.api.admin.general.payment.method.update.order_form.required'),
        'numeric'  => __('validations.api.admin.general.payment.method.update.order_form.numeric')
    ],
    'display_name' => [
        'required' => __('validations.api.admin.general.payment.method.update.display_name.required'),
        'array'    => __('validations.api.admin.general.payment.method.update.display_name.array')
    ],
    'duration_title' => [
        'required' => __('validations.api.admin.general.payment.method.update.duration_title.required'),
        'array'    => __('validations.api.admin.general.payment.method.update.duration_title.array')
    ],
    'duration_amount' => [
        'required' => __('validations.api.admin.general.payment.method.update.duration_amount.required'),
        'array'    => __('validations.api.admin.general.payment.method.update.duration_amount.array')
    ],
    'fee_title' => [
        'required' => __('validations.api.admin.general.payment.method.update.fee_title.required'),
        'array'    => __('validations.api.admin.general.payment.method.update.fee_title.array')
    ],
    'fee_amount' => [
        'required' => __('validations.api.admin.general.payment.method.update.fee_amount.required'),
        'array'    => __('validations.api.admin.general.payment.method.update.fee_amount.array')
    ],
    'country_places_ids' => [
        'required' => __('validations.api.admin.general.payment.method.update.country_places_ids.required'),
        'array'    => __('validations.api.admin.general.payment.method.update.country_places_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.general.payment.method.update.country_places_ids.*.required'),
            'integer'  => __('validations.api.admin.general.payment.method.update.country_places_ids.*.integer'),
            'exists'   => __('validations.api.admin.general.payment.method.update.country_places_ids.*.exists')
        ]
    ],
    'fields' => [
        'required' => __('validations.api.admin.general.payment.method.update.fields.required'),
        'array'    => __('validations.api.admin.general.payment.method.update.fields.array'),
        '*' => [
            'id' => [
                'integer' => __('validations.api.admin.general.payment.method.update.fields.*.id.integer'),
                'exists'  => __('validations.api.admin.general.payment.method.update.fields.*.id.exists')
            ],
            'type_id' => [
                'required' => __('validations.api.admin.general.payment.method.update.fields.*.type_id.required'),
                'integer'  => __('validations.api.admin.general.payment.method.update.fields.*.type_id.integer'),
                'between'  => __('validations.api.admin.general.payment.method.update.fields.*.type_id.between')
            ],
            'title' => [
                'required' => __('validations.api.admin.general.payment.method.update.fields.*.title.required'),
                'array'    => __('validations.api.admin.general.payment.method.update.fields.*.title.array')
            ],
            'placeholder' => [
                'required' => __('validations.api.admin.general.payment.method.update.fields.*.placeholder.required'),
                'array'    => __('validations.api.admin.general.payment.method.update.fields.*.placeholder.array')
            ],
            'tooltip' => [
                'required' => __('validations.api.admin.general.payment.method.update.fields.*.tooltip.required'),
                'array'    => __('validations.api.admin.general.payment.method.update.fields.*.tooltip.array')
            ]
        ]
    ],
    'image' => [
        'array' => __('validations.api.admin.general.payment.method.update.image.array'),
        'content'  => [
            'string' => __('validations.api.admin.general.payment.method.update.image.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.general.payment.method.update.image.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.general.payment.method.update.image.mime.string')
        ]
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.general.payment.method.update.result.error.find')
        ],
        'success' => __('validations.api.admin.general.payment.method.update.result.success')
    ]
];