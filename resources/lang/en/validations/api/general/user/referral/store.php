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
        'required' => __('validations.api.general.user.referral.store.parent_id.required'),
        'integer'  => __('validations.api.general.user.referral.store.parent_id.integer'),
        'exists'   => __('validations.api.general.user.referral.store.parent_id.exists')
    ],
    'child_id' => [
        'required' => __('validations.api.general.user.referral.store.child_id.required'),
        'integer'  => __('validations.api.general.user.referral.store.child_id.integer'),
        'exists'   => __('validations.api.general.user.referral.store.child_id.exists')
    ],
    'active' => [
        'required' => __('validations.api.general.user.referral.store.active.required'),
        'boolean'  => __('validations.api.general.user.referral.store.active.boolean')
    ],
    'result' => [
        'error' => [
            'create' => __('validations.api.general.user.referral.store.result.error.create')
        ],
        'success' => __('validations.api.general.user.referral.store.result.success')
    ]
];