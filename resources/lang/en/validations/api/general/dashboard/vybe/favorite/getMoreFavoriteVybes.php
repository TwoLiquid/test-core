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

    'type_id' => [
        'required' => __('validations.api.general.dashboard.vybe.favorite.getMoreFavoriteVybes.type_id.required'),
        'integer'  => __('validations.api.general.dashboard.vybe.favorite.getMoreFavoriteVybes.type_id.integer'),
        'between'  => __('validations.api.general.dashboard.vybe.favorite.getMoreFavoriteVybes.type_id.between')
    ],
    'per_page' => [
        'integer' => __('validations.api.general.dashboard.vybe.favorite.getMoreFavoriteVybes.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.general.dashboard.vybe.favorite.getMoreFavoriteVybes.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.general.dashboard.vybe.favorite.getMoreFavoriteVybes.result.success')
    ]
];