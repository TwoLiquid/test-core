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

    'overview_id' => [
        'integer' => __('validations.api.admin.user.finance.seller.saleOverview.index.overview_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.seller.saleOverview.index.overview_id.exists')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.user.finance.seller.saleOverview.index.date_from.string'),
        'date_format' => __('validations.api.admin.user.finance.seller.saleOverview.index.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.user.finance.seller.saleOverview.index.date_from.string'),
        'date_format' => __('validations.api.admin.user.finance.seller.saleOverview.index.date_to.date_format')
    ],
    'buyer' => [
        'string' => __('validations.api.admin.user.finance.seller.saleOverview.index.buyer.string')
    ],
    'order_item_id' => [
        'integer' => __('validations.api.admin.user.finance.seller.saleOverview.index.order_item_id.integer'),
        'exists'  => __('validations.api.admin.user.finance.seller.saleOverview.index.order_item_id.exists')
    ],
    'vybe_types_ids' => [
        'array' => __('validations.api.admin.user.finance.seller.saleOverview.index.vybe_types_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.finance.seller.saleOverview.index.vybe_types_ids.*.integer'),
            'between' => __('validations.api.admin.user.finance.seller.saleOverview.index.vybe_types_ids.*.between')
        ]
    ],
    'order_item_payment_statuses_ids' => [
        'array' => __('validations.api.admin.user.finance.seller.saleOverview.index.order_item_payment_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.finance.seller.saleOverview.index.order_item_payment_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.user.finance.seller.saleOverview.index.order_item_payment_statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.user.finance.seller.saleOverview.index.sort_by.string'),
        'in'     => __('validations.api.admin.user.finance.seller.saleOverview.index.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.user.finance.seller.saleOverview.index.sort_order.string'),
        'in'     => __('validations.api.admin.user.finance.seller.saleOverview.index.sort_order.in')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.user.finance.seller.saleOverview.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.user.finance.seller.saleOverview.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.user.finance.seller.saleOverview.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.user.finance.seller.saleOverview.index.result.success')
    ]
];