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
        'integer' => __('validations.api.admin.user.finance.buyer.tip.index.item_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.buyer.tip.index.item_id.exists')
    ],
    'vybe_type_id' => [
        'integer' => __('validations.api.admin.user.finance.buyer.tip.index.vybe_type_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.buyer.tip.index.vybe_type_id.exists')
    ],
    'seller' => [
        'string' => __('validations.api.admin.user.finance.buyer.tip.index.seller.string')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.user.finance.buyer.tip.index.date_from.string'),
        'date_format' => __('validations.api.admin.user.finance.buyer.tip.index.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.user.finance.buyer.tip.index.date_to.string'),
        'date_format' => __('validations.api.admin.user.finance.buyer.tip.index.date_to.date_format')
    ],
    'order_item_statuses_ids' => [
        'array' => __('validations.api.admin.user.finance.buyer.tip.index.order_item_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.finance.buyer.tip.index.order_item_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.user.finance.buyer.tip.index.order_item_statuses_ids.*.between')
        ]
    ],
    'invoice_id' => [
        'integer' => __('validations.api.admin.user.finance.buyer.tip.index.invoice_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.buyer.tip.index.invoice_id.exists')
    ],
    'invoice_statuses_ids' => [
        'array' => __('validations.api.admin.user.finance.buyer.tip.index.invoice_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.finance.buyer.tip.index.invoice_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.user.finance.buyer.tip.index.invoice_statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.user.finance.buyer.tip.index.sort_by.string'),
        'in'     => __('validations.api.admin.user.finance.buyer.tip.index.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.user.finance.buyer.tip.index.sort_order.string'),
        'in'     => __('validations.api.admin.user.finance.buyer.tip.index.sort_order.in')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.user.finance.buyer.tip.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.user.finance.buyer.tip.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.user.finance.buyer.tip.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.user.finance.buyer.tip.index.result.success')
    ]
];