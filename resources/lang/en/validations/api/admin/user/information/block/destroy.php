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
                'user'        => 'Failed to find a user.',
                'blockedUser' => 'Failed to find a blocked user.'
            ],
            'blockedUser' => 'Blocked user not belongs to a user.'
        ],
        'success' => 'Blocked user has been successfully detached.'
    ]
];
