<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Service Exception Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the exception class
    |
    */

    'checkPendingAccountRequests' => [
        'deactivationRequest' => __('exceptions.service.user.user.checkPendingAccountRequests.deactivationRequest'),
        'deletionRequest'     => __('exceptions.service.user.user.checkPendingAccountRequests.deletionRequest'),
        'unsuspendRequest'    => __('exceptions.service.user.user.checkPendingAccountRequests.unsuspendRequest')
    ],
    'isLastProfileRequestAccepted' => [
        'found' => __('exceptions.service.user.user.isLastProfileRequestAccepted.found')
    ]
];