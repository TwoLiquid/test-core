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

    // 3-rd step
    'schedules' => [
        'array' => __('validations.api.general.vybe.step.updateThirdStepChanges.schedules.array'),
        '*' => [
            'datetime_from' => [
                'date_format' => __('validations.api.general.vybe.step.updateThirdStepChanges.schedules.*.datetime_from.date_format')
            ],
            'datetime_to' => [
                'date_format' => __('validations.api.general.vybe.step.updateThirdStepChanges.schedules.*.datetime_to.date_format')
            ]
        ]
    ],
    'order_advance' => [
        'integer' => __('validations.api.general.vybe.step.updateThirdStepChanges.order_advance.integer')
    ],
    'result' => [
        'error' => [
            'find'           => __('validations.api.general.vybe.step.updateThirdStepChanges.result.error.find'),
            'publishRequest' => __('validations.api.general.vybe.step.updateThirdStepChanges.result.error.publishRequest'),
            'owner'          => __('validations.api.general.vybe.step.updateThirdStepChanges.result.error.owner'),
            'completed'      => __('validations.api.general.vybe.step.updateThirdStepChanges.result.error.completed')
        ],
        'success' => __('validations.api.general.vybe.step.updateThirdStepChanges.result.success')
    ]
];