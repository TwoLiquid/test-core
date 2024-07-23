<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Admin Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'result' => [
        'error' => [
            'find' => [
                'user'         => 'Failed to find a user.',
                'subscription' => 'Failed to find a following.'
            ],
            'subscription' => 'Following not belongs to a user.'
        ],
        'success' => 'User followings has been successfully detached.'
    ]
];
