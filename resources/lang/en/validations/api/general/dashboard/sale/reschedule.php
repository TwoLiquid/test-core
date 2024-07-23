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

    'datetime_from' => [
        'required'    => __('validations.api.general.dashboard.sale.reschedule.datetime_from.required'),
        'date_format' => __('validations.api.general.dashboard.sale.reschedule.datetime_from.date_format')
    ],
    'datetime_to' => [
        'required'    => __('validations.api.general.dashboard.sale.reschedule.datetime_to.required'),
        'date_format' => __('validations.api.general.dashboard.sale.reschedule.datetime_to.date_format')
    ],
    'result' => [
        'error' => [
            'find'   => __('validations.api.general.dashboard.sale.reschedule.result.error.find'),
            'seller' => __('validations.api.general.dashboard.sale.reschedule.result.error.seller'),
            'status' => __('validations.api.general.dashboard.sale.reschedule.result.error.status'),
            'timeslot' => [
                'busy' => __('validations.api.general.dashboard.sale.reschedule.result.error.timeslot.busy'),
                'full' => __('validations.api.general.dashboard.sale.reschedule.result.error.timeslot.full')
            ],
            'orderItemRescheduleRequest' => [
                'create' => __('validations.api.general.dashboard.sale.reschedule.result.error.orderItemRescheduleRequest.create')
            ]
        ],
        'success' => __('validations.api.general.dashboard.sale.reschedule.result.success')
    ]
];