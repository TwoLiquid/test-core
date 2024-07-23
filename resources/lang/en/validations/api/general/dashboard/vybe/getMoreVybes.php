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

    'types_ids' => [
        'array' => __('validations.api.general.dashboard.vybe.getMoreVybes.types_ids.array'),
        '*' => [
            'integer' => __('validations.api.general.dashboard.vybe.getMoreVybes.types_ids.*.integer'),
            'between' => __('validations.api.general.dashboard.vybe.getMoreVybes.types_ids.*.between')
        ]
    ],
    'statuses_ids' => [
        'array' => __('validations.api.general.dashboard.vybe.getMoreVybes.statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.general.dashboard.vybe.getMoreVybes.statuses_ids.*.integer'),
            'between' => __('validations.api.general.dashboard.vybe.getMoreVybes.statuses_ids.*.between')
        ]
    ],
    'per_page' => [
        'integer' => __('validations.api.general.dashboard.vybe.getMoreVybes.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.general.dashboard.vybe.getMoreVybes.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.general.dashboard.vybe.getMoreVybes.result.success')
    ]
];