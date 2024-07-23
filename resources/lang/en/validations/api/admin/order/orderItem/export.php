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
        'required' => __('validations.api.admin.order.orderItem.export.type.required'),
        'string'   => __('validations.api.admin.order.orderItem.export.type.string'),
        'in'       => __('validations.api.admin.order.orderItem.export.type.in')
    ],
    'order_item_id' => [
        'integer' => __('validations.api.admin.order.orderItem.export.order_item_id.integer'),
        'exists'  => __('validations.api.admin.order.orderItem.export.order_item_id.exists')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.order.orderItem.export.date_from.string'),
        'date_format' => __('validations.api.admin.order.orderItem.export.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.order.orderItem.export.date_from.string'),
        'date_format' => __('validations.api.admin.order.orderItem.export.date_to.date_format')
    ],
    'buyer' => [
        'string' => __('validations.api.admin.order.orderItem.export.buyer.string')
    ],
    'seller' => [
        'string' => __('validations.api.admin.order.orderItem.export.seller.string')
    ],
    'vybe_title' => [
        'string' => __('validations.api.admin.order.orderItem.export.vybe_title.string')
    ],
    'vybe_types_ids' => [
        'array' => __('validations.api.admin.order.orderItem.export.vybe_types_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.orderItem.export.vybe_types_ids.*.integer'),
            'between' => __('validations.api.admin.order.orderItem.export.vybe_types_ids.*.between')
        ]
    ],
    'order_item_statuses_ids' => [
        'array' => __('validations.api.admin.order.orderItem.export.order_item_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.orderItem.export.order_item_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.order.orderItem.export.order_item_statuses_ids.*.between')
        ]
    ],
    'order_item_payment_statuses_ids' => [
        'array' => __('validations.api.admin.order.orderItem.export.order_item_payment_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.orderItem.export.order_item_payment_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.order.orderItem.export.order_item_payment_statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.order.orderItem.export.sort_by.string'),
        'in'     => __('validations.api.admin.order.orderItem.export.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.order.orderItem.export.sort_order.string'),
        'in'     => __('validations.api.admin.order.orderItem.export.sort_order.in')
    ]
];