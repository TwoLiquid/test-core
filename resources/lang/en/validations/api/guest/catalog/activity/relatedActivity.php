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
            'find' => [
                'activity' => __('validations.api.guest.catalog.activity.relatedActivity.result.error.find.activity'),
                'related'  => __('validations.api.guest.catalog.activity.relatedActivity.result.error.find.related')
            ]
        ],
        'success' => __('validations.api.guest.catalog.activity.relatedActivity.result.success')
    ]
];