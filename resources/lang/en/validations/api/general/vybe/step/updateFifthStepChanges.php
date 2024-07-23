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
        'integer' => __('validations.api.general.vybe.step.updateFifthStepChanges.access_id.integer'),
        'between' => __('validations.api.general.vybe.step.updateFifthStepChanges.access_id.between')
    ],
    'access_password' => [
        'string' => __('validations.api.general.vybe.step.updateFifthStepChanges.access_password.string')
    ],
    'showcase_id' => [
        'integer' => __('validations.api.general.vybe.step.updateFifthStepChanges.showcase_id.integer'),
        'between' => __('validations.api.general.vybe.step.updateFifthStepChanges.showcase_id.between')
    ],
    'status_id' => [
        'integer' => __('validations.api.general.vybe.step.updateFifthStepChanges.status_id.integer'),
        'in'      => __('validations.api.general.vybe.step.updateFifthStepChanges.status_id.in')
    ],
    'result' => [
        'error' => [
            'find'           => __('validations.api.general.vybe.step.updateFifthStepChanges.result.error.find'),
            'publishRequest' => __('validations.api.general.vybe.step.updateFifthStepChanges.result.error.publishRequest'),
            'owner'          => __('validations.api.general.vybe.step.updateFifthStepChanges.result.error.owner'),
            'completed'      => __('validations.api.general.vybe.step.updateFifthStepChanges.result.error.completed')
        ],
        'success' => __('validations.api.general.vybe.step.updateFifthStepChanges.result.success')
    ]
];