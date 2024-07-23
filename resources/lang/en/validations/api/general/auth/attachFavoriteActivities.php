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
        'required' => __('validations.api.general.auth.attachFavoriteActivities.ids.required'),
        'array'    => __('validations.api.general.auth.attachFavoriteActivities.ids.array'),
        '*' => [
            'required' => __('validations.api.general.auth.attachFavoriteActivities.ids.*.required'),
            'integer'  => __('validations.api.general.auth.attachFavoriteActivities.ids.*.integer'),
            'exists'   => __('validations.api.general.auth.attachFavoriteActivities.ids.*.exists')
        ]
    ],
    'detaching' => [
        'boolean' => __('validations.api.general.auth.attachFavoriteActivities.detaching.boolean')
    ],
    'result' => [
        'success' => __('validations.api.general.auth.attachFavoriteActivities.result.success')
    ]
];