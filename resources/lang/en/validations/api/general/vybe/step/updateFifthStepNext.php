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

    // 5-th step
    'access_id' => [
        'required' => __('validations.api.general.vybe.step.updateFifthStepNext.access_id.required'),
        'integer'  => __('validations.api.general.vybe.step.updateFifthStepNext.access_id.integer'),
        'between'  => __('validations.api.general.vybe.step.updateFifthStepNext.access_id.between')
    ],
    'access_password' => [
        'string' => __('validations.api.general.vybe.step.updateFifthStepNext.access_password.string')
    ],
    'showcase_id' => [
        'required' => __('validations.api.general.vybe.step.updateFifthStepNext.showcase_id.required'),
        'integer'  => __('validations.api.general.vybe.step.updateFifthStepNext.showcase_id.integer'),
        'between'  => __('validations.api.general.vybe.step.updateFifthStepNext.showcase_id.between')
    ],
    'status_id' => [
        'required' => __('validations.api.general.vybe.step.updateFifthStepNext.status_id.required'),
        'integer'  => __('validations.api.general.vybe.step.updateFifthStepNext.status_id.integer'),
        'in'       => __('validations.api.general.vybe.step.updateFifthStepNext.status_id.in')
    ],
    'result' => [
        'error' => [
            'find'           => __('validations.api.general.vybe.step.updateFifthStepNext.result.error.find'),
            'publishRequest' => __('validations.api.general.vybe.step.updateFifthStepNext.result.error.publishRequest'),
            'owner'          => __('validations.api.general.vybe.step.updateFifthStepNext.result.error.owner'),
            'completed'      => __('validations.api.general.vybe.step.updateFifthStepNext.result.error.completed'),
            'stepForward'    => __('validations.api.general.vybe.step.updateFifthStepNext.result.error.stepForward'),
            'accessPassword' => [
                'required' => __('validations.api.general.vybe.step.updateFifthStepNext.result.error.accessPassword.required'),
                'excess'   => __('validations.api.general.vybe.step.updateFifthStepNext.result.error.accessPassword.excess')
            ]
        ],
        'success' => [
            'completed' => __('validations.api.general.vybe.step.updateFifthStepNext.result.success.completed'),
            'deleted'   => __('validations.api.general.vybe.step.updateFifthStepNext.result.success.deleted')
        ]
    ]
];