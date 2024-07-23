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
        'integer' => __('validations.api.admin.invoice.seller.index.invoice_id.integer'),
        'exists'  => __('validations.api.admin.invoice.seller.index.invoice_id.exists')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.invoice.seller.index.date_from.string'),
        'date_format' => __('validations.api.admin.invoice.seller.index.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.invoice.seller.index.date_from.string'),
        'date_format' => __('validations.api.admin.invoice.seller.index.date_to.date_format')
    ],
    'vybe_version' => [
        'string' => __('validations.api.admin.invoice.seller.index.vybe_version.string')
    ],
    'buyer' => [
        'string' => __('validations.api.admin.invoice.seller.index.buyer.string')
    ],
    'seller' => [
        'string' => __('validations.api.admin.invoice.seller.index.seller.string')
    ],
    'vybe_types_ids' => [
        'array' => __('validations.api.admin.invoice.seller.index.vybe_types_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.invoice.seller.index.vybe_types_ids.*.integer'),
            'between' => __('validations.api.admin.invoice.seller.index.vybe_types_ids.*.between')
        ]
    ],
    'order_item_statuses_ids' => [
        'array' => __('validations.api.admin.invoice.seller.index.order_item_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.invoice.seller.index.order_item_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.invoice.seller.index.order_item_statuses_ids.*.between')
        ]
    ],
    'invoice_statuses_ids' => [
        'array' => __('validations.api.admin.invoice.seller.index.invoice_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.invoice.seller.index.invoice_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.invoice.seller.index.invoice_statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.invoice.seller.index.sort_by.string'),
        'in'     => __('validations.api.admin.invoice.seller.index.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.invoice.seller.index.sort_order.string'),
        'in'     => __('validations.api.admin.invoice.seller.index.sort_order.in')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.invoice.seller.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.invoice.seller.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.invoice.seller.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.invoice.seller.index.result.success')
    ]
];