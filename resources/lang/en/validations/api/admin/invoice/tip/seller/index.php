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

    'item_id' => [
        'integer' => __('validations.api.admin.invoice.tip.seller.index.item_id.integer')
    ],
    'vybe_type_id' => [
        'integer' => __('validations.api.admin.invoice.tip.seller.index.vybe_type_id.integer'),
        'exists'  => __('validations.api.admin.invoice.tip.seller.index.vybe_type_id.exists')
    ],
    'buyer' => [
        'string' => __('validations.api.admin.invoice.tip.seller.index.buyer.string')
    ],
    'seller' => [
        'string' => __('validations.api.admin.invoice.tip.seller.index.seller.string')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.invoice.tip.seller.index.date_from.string'),
        'date_format' => __('validations.api.admin.invoice.tip.seller.index.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.invoice.tip.seller.index.date_from.string'),
        'date_format' => __('validations.api.admin.invoice.tip.seller.index.date_to.date_format')
    ],
    'order_item_statuses_ids' => [
        'array' => __('validations.api.admin.invoice.tip.seller.index.order_item_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.invoice.tip.seller.index.order_item_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.invoice.tip.seller.index.order_item_statuses_ids.*.between')
        ]
    ],
    'invoice_id' => [
        'integer' => __('validations.api.admin.invoice.tip.seller.index.invoice_id.integer')
    ],
    'invoice_statuses_ids' => [
        'array' => __('validations.api.admin.invoice.tip.seller.index.invoice_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.invoice.tip.seller.index.invoice_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.invoice.tip.seller.index.invoice_statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.invoice.tip.seller.index.sort_by.string'),
        'in'     => __('validations.api.admin.invoice.tip.seller.index.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.invoice.tip.seller.index.sort_order.string'),
        'in'     => __('validations.api.admin.invoice.tip.seller.index.sort_order.in')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.invoice.tip.seller.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.invoice.tip.seller.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.invoice.tip.seller.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.invoice.tip.seller.index.result.success')
    ]
];