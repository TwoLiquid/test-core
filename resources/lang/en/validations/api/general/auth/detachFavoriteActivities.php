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

    'ids' => [
        'required' => __('validations.api.general.auth.detachFavoriteActivities.ids.required'),
        'array'    => __('validations.api.general.auth.detachFavoriteActivities.ids.array'),
        '*' => [
            'required' => __('validations.api.general.auth.detachFavoriteActivities.ids.*.required'),
            'integer'  => __('validations.api.general.auth.detachFavoriteActivities.ids.*.integer'),
            'exists'   => __('validations.api.general.auth.detachFavoriteActivities.ids.*.exists')
        ]
    ],
    'result' => [
        'success' => __('validations.api.general.auth.detachFavoriteActivities.result.success')
    ]
];