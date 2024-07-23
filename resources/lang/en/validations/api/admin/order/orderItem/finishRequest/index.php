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

    'order_item_id' => [
        'integer' => __('validations.api.admin.order.orderItem.finishRequest.index.order_item_id.integer'),
        'exists'  => __('validations.api.admin.order.orderItem.finishRequest.index.order_item_id.exists')
    ],
    'from_request_datetime' => [
        'string'      => __('validations.api.admin.order.orderItem.finishRequest.index.from_request_datetime.string'),
        'date_format' => __('validations.api.admin.order.orderItem.finishRequest.index.from_request_datetime.date_format')
    ],
    'to_request_datetime' => [
        'string'      => __('validations.api.admin.order.orderItem.finishRequest.index.to_request_datetime.string'),
        'date_format' => __('validations.api.admin.order.orderItem.finishRequest.index.to_request_datetime.date_format')
    ],
    'vybe_title' => [
        'string' => __('validations.api.admin.order.orderItem.finishRequest.index.vybe_title.string')
    ],
    'order_date_from' => [
        'string'      => __('validations.api.admin.order.orderItem.finishRequest.index.order_date_from.string'),
        'date_format' => __('validations.api.admin.order.orderItem.finishRequest.index.order_date_from.date_format')
    ],
    'order_date_to' => [
        'string'      => __('validations.api.admin.order.orderItem.finishRequest.index.order_date_to.string'),
        'date_format' => __('validations.api.admin.order.orderItem.finishRequest.index.order_date_to.date_format')
    ],
    'buyer' => [
        'string' => __('validations.api.admin.order.orderItem.finishRequest.index.buyer.string')
    ],
    'seller' => [
        'string' => __('validations.api.admin.order.orderItem.finishRequest.index.seller.string')
    ],
    'from_order_item_statuses_ids' => [
        'array' => __('validations.api.admin.order.orderItem.finishRequest.index.from_order_item_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.orderItem.finishRequest.index.from_order_item_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.order.orderItem.finishRequest.index.from_order_item_statuses_ids.*.between')
        ]
    ],
    'to_order_item_statuses_ids' => [
        'array' => __('validations.api.admin.order.orderItem.finishRequest.index.to_order_item_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.orderItem.finishRequest.index.to_order_item_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.order.orderItem.finishRequest.index.to_order_item_statuses_ids.*.between')
        ]
    ],
    'order_item_request_action_ids' => [
        'array' => __('validations.api.admin.order.orderItem.finishRequest.index.order_item_request_action_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.orderItem.finishRequest.index.order_item_request_action_ids.*.integer'),
            'between' => __('validations.api.admin.order.orderItem.finishRequest.index.order_item_request_action_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.order.orderItem.finishRequest.index.sort_by.string'),
        'in'     => __('validations.api.admin.order.orderItem.finishRequest.index.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.order.orderItem.finishRequest.index.sort_order.string'),
        'in'     => __('validations.api.admin.order.orderItem.finishRequest.index.sort_order.in')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.order.orderItem.finishRequest.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.order.orderItem.finishRequest.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.order.orderItem.finishRequest.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.order.orderItem.finishRequest.index.result.success')
    ]
];