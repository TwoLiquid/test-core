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
        'integer' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.order_item_id.integer'),
        'exists'  => __('validations.api.admin.order.orderItem.rescheduleRequest.index.order_item_id.exists')
    ],
    'from_request_datetime' => [
        'string'      => __('validations.api.admin.order.orderItem.rescheduleRequest.index.from_request_datetime.string'),
        'date_format' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.from_request_datetime.date_format')
    ],
    'to_request_datetime' => [
        'string'      => __('validations.api.admin.order.orderItem.rescheduleRequest.index.to_request_datetime.string'),
        'date_format' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.to_request_datetime.date_format')
    ],
    'vybe_title' => [
        'string' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.vybe_title.string')
    ],
    'order_date_from' => [
        'string'      => __('validations.api.admin.order.orderItem.rescheduleRequest.index.order_date_from.string'),
        'date_format' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.order_date_from.date_format')
    ],
    'order_date_to' => [
        'string'      => __('validations.api.admin.order.orderItem.rescheduleRequest.index.order_date_to.string'),
        'date_format' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.order_date_to.date_format')
    ],
    'buyer' => [
        'string' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.buyer.string')
    ],
    'seller' => [
        'string' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.seller.string')
    ],
    'from_order_item_statuses_ids' => [
        'array' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.from_order_item_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.from_order_item_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.from_order_item_statuses_ids.*.between')
        ]
    ],
    'to_order_item_statuses_ids' => [
        'array' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.to_order_item_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.to_order_item_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.to_order_item_statuses_ids.*.between')
        ]
    ],
    'order_item_request_action_ids' => [
        'array' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.order_item_request_action_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.order_item_request_action_ids.*.integer'),
            'between' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.order_item_request_action_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.sort_by.string'),
        'in'     => __('validations.api.admin.order.orderItem.rescheduleRequest.index.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.sort_order.string'),
        'in'     => __('validations.api.admin.order.orderItem.rescheduleRequest.index.sort_order.in')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.order.orderItem.rescheduleRequest.index.result.success')
    ]
];