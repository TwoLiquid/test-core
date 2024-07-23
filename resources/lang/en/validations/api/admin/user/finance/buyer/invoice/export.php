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

    'id' => [
        'required' => __('validations.api.admin.user.finance.buyer.invoice.export.id.required'),
        'integer'  => __('validations.api.admin.user.finance.buyer.invoice.export.id.integer'),
        'exists'   => __('validations.api.admin.user.finance.buyer.invoice.export.id.exists')
    ],
    'type' => [
        'required' => __('validations.api.admin.user.finance.buyer.invoice.export.type.required'),
        'string'   => __('validations.api.admin.user.finance.buyer.invoice.export.type.string'),
        'in'       => __('validations.api.admin.user.finance.buyer.invoice.export.type.in')
    ],
    'invoice_id' => [
        'integer' => __('validations.api.admin.user.finance.buyer.invoice.export.invoice_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.buyer.invoice.export.invoice_id.exists')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.user.finance.buyer.invoice.export.date_from.string'),
        'date_format' => __('validations.api.admin.user.finance.buyer.invoice.export.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.user.finance.buyer.invoice.export.date_from.string'),
        'date_format' => __('validations.api.admin.user.finance.buyer.invoice.export.date_to.date_format')
    ],
    'order_overview_id' => [
        'integer' => __('validations.api.admin.user.finance.buyer.invoice.export.order_overview_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.buyer.invoice.export.order_overview_id.exists')
    ],
    'seller' => [
        'string' => __('validations.api.admin.user.finance.buyer.invoice.export.seller.string')
    ],
    'vybe_types_ids' => [
        'array' => __('validations.api.admin.user.finance.buyer.invoice.export.vybe_types_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.finance.buyer.invoice.export.vybe_types_ids.*.integer'),
            'between' => __('validations.api.admin.user.finance.buyer.invoice.export.vybe_types_ids.*.between')
        ]
    ],
    'order_item_payment_statuses_ids' => [
        'array' => __('validations.api.admin.user.finance.buyer.invoice.export.order_item_payment_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.finance.buyer.invoice.export.order_item_payment_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.user.finance.buyer.invoice.export.order_item_payment_statuses_ids.*.between')
        ]
    ],
    'invoice_statuses_ids' => [
        'array' => __('validations.api.admin.user.finance.buyer.invoice.export.invoice_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.finance.buyer.invoice.export.invoice_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.user.finance.buyer.invoice.export.invoice_statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.user.finance.buyer.invoice.export.sort_by.string'),
        'in'     => __('validations.api.admin.user.finance.buyer.invoice.export.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.user.finance.buyer.invoice.export.sort_order.string'),
        'in'     => __('validations.api.admin.user.finance.buyer.invoice.export.sort_order.in')
    ]
];