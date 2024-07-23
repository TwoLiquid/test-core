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
        'required' => __('validations.api.admin.user.finance.buyer.tip.export.id.required'),
        'integer'  => __('validations.api.admin.user.finance.buyer.tip.export.id.integer'),
        'exists'   => __('validations.api.admin.user.finance.buyer.tip.export.id.exists')
    ],
    'type' => [
        'required' => __('validations.api.admin.user.finance.buyer.tip.export.type.required'),
        'string'   => __('validations.api.admin.user.finance.buyer.tip.export.type.string'),
        'in'       => __('validations.api.admin.user.finance.buyer.tip.export.type.in')
    ],
    'item_id' => [
        'integer' => __('validations.api.admin.user.finance.buyer.tip.export.item_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.buyer.tip.export.item_id.exists')
    ],
    'vybe_type_id' => [
        'integer' => __('validations.api.admin.user.finance.buyer.tip.export.vybe_type_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.buyer.tip.export.vybe_type_id.exists')
    ],
    'seller' => [
        'string' => __('validations.api.admin.user.finance.buyer.tip.export.seller.string')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.user.finance.buyer.tip.export.date_from.string'),
        'date_format' => __('validations.api.admin.user.finance.buyer.tip.export.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.user.finance.buyer.tip.export.date_to.string'),
        'date_format' => __('validations.api.admin.user.finance.buyer.tip.export.date_to.date_format')
    ],
    'order_item_statuses_ids' => [
        'array' => __('validations.api.admin.user.finance.buyer.tip.export.order_item_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.finance.buyer.tip.export.order_item_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.user.finance.buyer.tip.export.order_item_statuses_ids.*.between')
        ]
    ],
    'invoice_id' => [
        'integer' => __('validations.api.admin.user.finance.buyer.tip.export.invoice_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.buyer.tip.export.invoice_id.exists')
    ],
    'invoice_statuses_ids' => [
        'array' => __('validations.api.admin.user.finance.buyer.tip.export.invoice_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.finance.buyer.tip.export.invoice_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.user.finance.buyer.tip.export.invoice_statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.user.finance.buyer.tip.export.sort_by.string'),
        'in'     => __('validations.api.admin.user.finance.buyer.tip.export.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.user.finance.buyer.tip.export.sort_order.string'),
        'in'     => __('validations.api.admin.user.finance.buyer.tip.export.sort_order.in')
    ]
];