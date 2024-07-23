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
        'required' => __('validations.api.admin.order.saleOverview.export.type.required'),
        'string'   => __('validations.api.admin.order.saleOverview.export.type.string'),
        'in'       => __('validations.api.admin.order.saleOverview.export.type.in')
    ],
    'overview_id' => [
        'integer' => __('validations.api.admin.order.saleOverview.export.overview_id.integer'),
        'exists'  => __('validations.api.admin.order.saleOverview.export.overview_id.exists')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.order.saleOverview.export.date_from.string'),
        'date_format' => __('validations.api.admin.order.saleOverview.export.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.order.saleOverview.export.date_from.string'),
        'date_format' => __('validations.api.admin.order.saleOverview.export.date_to.date_format')
    ],
    'buyer' => [
        'string' => __('validations.api.admin.order.saleOverview.export.buyer.string')
    ],
    'seller' => [
        'string' => __('validations.api.admin.order.saleOverview.export.seller.string')
    ],
    'order_item_id' => [
        'integer' => __('validations.api.admin.order.saleOverview.export.order_item_id.integer'),
        'exists'  => __('validations.api.admin.order.saleOverview.export.order_item_id.exists')
    ],
    'vybe_types_ids' => [
        'array' => __('validations.api.admin.order.saleOverview.export.vybe_types_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.saleOverview.export.vybe_types_ids.*.integer'),
            'between' => __('validations.api.admin.order.saleOverview.export.vybe_types_ids.*.between')
        ]
    ],
    'order_item_payment_statuses_ids' => [
        'array' => __('validations.api.admin.order.saleOverview.export.order_item_payment_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.saleOverview.export.order_item_payment_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.order.saleOverview.export.order_item_payment_statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.order.saleOverview.export.sort_by.string'),
        'in'     => __('validations.api.admin.order.saleOverview.export.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.order.saleOverview.export.sort_order.string'),
        'in'     => __('validations.api.admin.order.saleOverview.export.sort_order.in')
    ]
];