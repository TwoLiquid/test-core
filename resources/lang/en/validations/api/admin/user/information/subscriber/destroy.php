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
                'user'       => 'Failed to find a user.',
                'subscriber' => 'Failed to find a follower.'
            ],
            'subscriber' => 'Follower not belongs to a user.'
        ],
        'success' => 'User follower has been successfully detached.'
    ]
];
