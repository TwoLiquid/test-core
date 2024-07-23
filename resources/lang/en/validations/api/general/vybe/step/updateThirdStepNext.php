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
        'required' => __('validations.api.general.vybe.step.updateThirdStepNext.schedules.required'),
        'array'    => __('validations.api.general.vybe.step.updateThirdStepNext.schedules.array'),
        '*'        => [
            'datetime_from' => [
                'date_format' => __('validations.api.general.vybe.step.updateThirdStepNext.schedules.*.datetime_from.date_format')
            ],
            'datetime_to' => [
                'date_format' => __('validations.api.general.vybe.step.updateThirdStepNext.schedules.*.datetime_to.date_format')
            ],
        ]
    ],
    'order_advance' => [
        'required' => __('validations.api.general.vybe.step.updateThirdStepNext.order_advance.required'),
        'integer'  => __('validations.api.general.vybe.step.updateThirdStepNext.order_advance.integer')
    ],
    'result' => [
        'error' => [
            'find'           => __('validations.api.general.vybe.step.updateThirdStepNext.result.error.find'),
            'publishRequest' => __('validations.api.general.vybe.step.updateThirdStepNext.result.error.publishRequest'),
            'owner'          => __('validations.api.general.vybe.step.updateThirdStepNext.result.error.owner'),
            'completed'      => __('validations.api.general.vybe.step.updateThirdStepNext.result.error.completed'),
            'stepForward'    => __('validations.api.general.vybe.step.updateThirdStepNext.result.error.stepForward'),
            'vybeType'       => __('validations.api.general.vybe.step.updateThirdStepNext.result.error.vybeType')
        ],
        'success' => __('validations.api.general.vybe.step.updateThirdStepNext.result.success')
    ]
];