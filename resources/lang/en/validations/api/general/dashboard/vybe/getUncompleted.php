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

    'result' => [
        'error' => [
            'find'          => __('validations.api.general.dashboard.vybe.getUncompleted.result.error.find'),
            'belongsToUser' => __('validations.api.general.dashboard.vybe.getUncompleted.result.error.belongsToUser'),
            'step'          => __('validations.api.general.dashboard.vybe.getUncompleted.result.error.step')
        ],
        'success' => __('validations.api.general.dashboard.vybe.getUncompleted.result.success')
    ]
];