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

    'parent_id' => [
        'integer' => __('validations.api.general.user.referral.update.parent_id.integer'),
        'exists'  => __('validations.api.general.user.referral.update.parent_id.exists')
    ],
    'child_id' => [
        'integer' => __('validations.api.general.user.referral.update.child_id.integer'),
        'exists'  => __('validations.api.general.user.referral.update.child_id.exists')
    ],
    'active' => [
        'boolean' => __('validations.api.general.user.referral.update.active.boolean')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.general.user.referral.update.result.error.find')
        ],
        'success' => __('validations.api.general.user.referral.update.result.success')
    ]
];