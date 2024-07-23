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

    'invoice_id' => [
        'integer' => __('validations.api.admin.invoice.buyer.index.invoice_id.integer'),
        'exists'  => __('validations.api.admin.invoice.buyer.index.invoice_id.exists')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.invoice.buyer.index.date_from.string'),
        'date_format' => __('validations.api.admin.invoice.buyer.index.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.invoice.buyer.index.date_from.string'),
        'date_format' => __('validations.api.admin.invoice.buyer.index.date_to.date_format')
    ],
    'order_overview_id' => [
        'integer' => __('validations.api.admin.invoice.buyer.index.order_overview_id.integer'),
        'exists'  => __('validations.api.admin.invoice.buyer.index.order_overview_id.exists')
    ],
    'buyer' => [
        'string' => __('validations.api.admin.invoice.buyer.index.buyer.string')
    ],
    'seller' => [
        'string' => __('validations.api.admin.invoice.buyer.index.seller.string')
    ],
    'vybe_types_ids' => [
        'array' => __('validations.api.admin.invoice.buyer.index.vybe_types_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.invoice.buyer.index.vybe_types_ids.*.integer'),
            'between' => __('validations.api.admin.invoice.buyer.index.vybe_types_ids.*.between')
        ]
    ],
    'order_item_payment_statuses_ids' => [
        'array' => __('validations.api.admin.invoice.buyer.index.order_item_payment_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.invoice.buyer.index.order_item_payment_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.invoice.buyer.index.order_item_payment_statuses_ids.*.between')
        ]
    ],
    'invoice_statuses_ids' => [
        'array' => __('validations.api.admin.invoice.buyer.index.invoice_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.invoice.buyer.index.invoice_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.invoice.buyer.index.invoice_statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.invoice.buyer.index.sort_by.string'),
        'in'     => __('validations.api.admin.invoice.buyer.index.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.invoice.buyer.index.sort_order.string'),
        'in'     => __('validations.api.admin.invoice.buyer.index.sort_order.in')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.invoice.buyer.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.invoice.buyer.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.invoice.buyer.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.invoice.buyer.index.result.success')
    ]
];