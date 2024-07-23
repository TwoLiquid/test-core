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
        'integer' => __('validations.api.admin.order.tip.index.item_id.integer'),
        'exists'  => __('validations.api.admin.order.tip.index.item_id.exists')
    ],
    'vybe_types_ids' => [
        'array' => __('validations.api.admin.order.tip.index.vybe_types_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.tip.index.vybe_types_ids.*.integer'),
            'between' => __('validations.api.admin.order.tip.index.vybe_types_ids.*.between')
        ]
    ],
    'buyer' => [
        'string' => __('validations.api.admin.order.tip.index.buyer.string')
    ],
    'seller' => [
        'string' => __('validations.api.admin.order.tip.index.seller.string')
    ],
    'payment_methods_ids' => [
        'array' => __('validations.api.admin.order.tip.index.payment_methods_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.tip.index.payment_methods_ids.*.integer'),
            'exists'  => __('validations.api.admin.order.tip.index.payment_methods_ids.*.exists')
        ]
    ],
    'order_item_statuses_ids' => [
        'array' => __('validations.api.admin.order.tip.index.order_item_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.tip.index.order_item_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.order.tip.index.order_item_statuses_ids.*.between')
        ]
    ],
    'tip_invoice_buyer_id' => [
        'integer' => __('validations.api.admin.order.tip.index.tip_invoice_buyer_id.integer'),
        'exists'  => __('validations.api.admin.order.tip.index.tip_invoice_buyer_id.exists')
    ],
    'tip_invoice_buyer_statuses_ids' => [
        'array' => __('validations.api.admin.order.tip.index.tip_invoice_buyer_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.tip.index.tip_invoice_buyer_statuses_ids.*.integer')
        ]
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.order.tip.index.date_from.string'),
        'date_format' => __('validations.api.admin.order.tip.index.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.order.tip.index.date_from.string'),
        'date_format' => __('validations.api.admin.order.tip.index.date_to.date_format')
    ],
    'tip_invoice_seller_id' => [
        'integer' => __('validations.api.admin.order.tip.index.tip_invoice_seller_id.integer'),
        'exists'  => __('validations.api.admin.order.tip.index.tip_invoice_seller_id.exists')
    ],
    'tip_invoice_seller_statuses_ids' => [
        'array' => __('validations.api.admin.order.tip.index.tip_invoice_seller_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.tip.index.tip_invoice_seller_statuses_ids.*.integer')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.order.tip.index.sort_by.string'),
        'in'     => __('validations.api.admin.order.tip.index.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.order.tip.index.sort_order.string'),
        'in'     => __('validations.api.admin.order.tip.index.sort_order.in')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.order.tip.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.order.tip.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.order.tip.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.order.tip.index.result.success')
    ]
];