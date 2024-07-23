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

    'type' => [
        'required' => __('validations.api.admin.order.tip.export.type.required'),
        'string'   => __('validations.api.admin.order.tip.export.type.string'),
        'in'       => __('validations.api.admin.order.tip.export.type.in')
    ],
    'item_id' => [
        'integer' => __('validations.api.admin.order.tip.export.item_id.integer'),
        'exists'  => __('validations.api.admin.order.tip.export.item_id.exists')
    ],
    'vybe_types_ids' => [
        'array' => __('validations.api.admin.order.tip.export.vybe_types_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.tip.export.vybe_types_ids.*.integer'),
            'between' => __('validations.api.admin.order.tip.export.vybe_types_ids.*.between')
        ]
    ],
    'buyer' => [
        'string' => __('validations.api.admin.order.tip.export.buyer.string')
    ],
    'seller' => [
        'string' => __('validations.api.admin.order.tip.export.seller.string')
    ],
    'payment_methods_ids' => [
        'array' => __('validations.api.admin.order.tip.export.payment_methods_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.tip.export.payment_methods_ids.*.integer'),
            'exists'  => __('validations.api.admin.order.tip.export.payment_methods_ids.*.exists')
        ]
    ],
    'order_item_statuses_ids' => [
        'array' => __('validations.api.admin.order.tip.export.order_item_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.tip.export.order_item_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.order.tip.export.order_item_statuses_ids.*.between')
        ]
    ],
    'tip_invoice_buyer_id' => [
        'integer' => __('validations.api.admin.order.tip.export.tip_invoice_buyer_id.integer'),
        'exists'  => __('validations.api.admin.order.tip.export.tip_invoice_buyer_id.exists')
    ],
    'tip_invoice_buyer_statuses_ids' => [
        'array' => __('validations.api.admin.order.tip.export.tip_invoice_buyer_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.tip.export.tip_invoice_buyer_statuses_ids.*.integer')
        ]
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.order.tip.export.date_from.string'),
        'date_format' => __('validations.api.admin.order.tip.export.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.order.tip.export.date_from.string'),
        'date_format' => __('validations.api.admin.order.tip.export.date_to.date_format')
    ],
    'tip_invoice_seller_id' => [
        'integer' => __('validations.api.admin.order.tip.export.tip_invoice_seller_id.integer'),
        'exists'  => __('validations.api.admin.order.tip.export.tip_invoice_seller_id.exists')
    ],
    'tip_invoice_seller_statuses_ids' => [
        'array' => __('validations.api.admin.order.tip.export.tip_invoice_seller_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.tip.export.tip_invoice_seller_statuses_ids.*.integer')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.order.tip.export.sort_by.string'),
        'in'     => __('validations.api.admin.order.tip.export.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.order.tip.export.sort_order.string'),
        'in'     => __('validations.api.admin.order.tip.export.sort_order.in')
    ]
];