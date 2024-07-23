<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Admin Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'request_id' => [
        'string' => __('validations.api.admin.request.vybe.changeRequest.index.request_id.string'),
        'exists' => __('validations.api.admin.request.vybe.changeRequest.index.request_id.exists')
    ],
    'vybe_version' => [
        'integer' => __('validations.api.admin.request.vybe.changeRequest.index.vybe_version.integer'),
        'exists'  => __('validations.api.admin.request.vybe.changeRequest.index.vybe_version.exists')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.request.vybe.changeRequest.index.date_from.string'),
        'date_format' => __('validations.api.admin.request.vybe.changeRequest.index.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.request.vybe.changeRequest.index.date_from.string'),
        'date_format' => __('validations.api.admin.request.vybe.changeRequest.index.date_to.date_format')
    ],
    'username' => [
        'string' => __('validations.api.admin.request.vybe.changeRequest.index.username.string')
    ],
    'sales' => [
        'integer' => __('validations.api.admin.request.vybe.changeRequest.index.sales.integer')
    ],
    'languages_ids' => [
        'array' => __('validations.api.admin.request.vybe.changeRequest.index.languages_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.request.vybe.changeRequest.index.languages_ids.*.required'),
            'integer'  => __('validations.api.admin.request.vybe.changeRequest.index.languages_ids.*.integer')
        ]
    ],
    'vybe_statuses_ids' => [
        'array' => __('validations.api.admin.request.vybe.changeRequest.index.vybe_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.request.vybe.changeRequest.index.vybe_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.request.vybe.changeRequest.index.vybe_statuses_ids.*.between')
        ]
    ],
    'request_statuses_ids' => [
        'array' => __('validations.api.admin.request.vybe.changeRequest.index.request_statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.request.vybe.changeRequest.index.request_statuses_ids.*.integer'),
            'between' => __('validations.api.admin.request.vybe.changeRequest.index.request_statuses_ids.*.between')
        ]
    ],
    'admin' => [
        'string' => __('validations.api.admin.request.vybe.changeRequest.index.admin.string')
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.request.vybe.changeRequest.index.sort_by.string'),
        'in'     => __('validations.api.admin.request.vybe.changeRequest.index.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.request.vybe.changeRequest.index.sort_order.string'),
        'in'     => __('validations.api.admin.request.vybe.changeRequest.index.sort_order.in')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.request.vybe.changeRequest.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.request.vybe.changeRequest.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.request.vybe.changeRequest.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.request.vybe.changeRequest.index.result.success')
    ]
];