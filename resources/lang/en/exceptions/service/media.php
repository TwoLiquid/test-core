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

    'validateUserAvatar' => [
        'allowedMimes' => __('exceptions.service.media.validateUserAvatar.allowedMimes'),
        'maxSize'      => __('exceptions.service.media.validateUserAvatar.maxSize')
    ],
    'validateUserBackground' => [
        'allowedMimes' => __('exceptions.service.media.validateUserBackground.allowedMimes'),
        'maxSize'      => __('exceptions.service.media.validateUserBackground.maxSize')
    ],
    'validateUserImage' => [
        'allowedMimes' => __('exceptions.service.media.validateUserImage.allowedMimes'),
        'maxSize'      => __('exceptions.service.media.validateUserImage.maxSize')
    ],
    'validateUserVideo' => [
        'allowedMimes' => __('exceptions.service.media.validateUserVideo.allowedMimes'),
        'maxSize'      => __('exceptions.service.media.validateUserVideo.maxSize')
    ],
    'validateUserVoiceSample' => [
        'allowedMimes' => __('exceptions.service.media.validateUserVoiceSample.allowedMimes'),
        'maxSize'      => __('exceptions.service.media.validateUserVoiceSample.maxSize')
    ],
    'validateUserIdVerificationImage' => [
        'allowedMimes' => __('exceptions.service.media.validateUserIdVerificationImage.allowedMimes'),
        'maxSize'      => __('exceptions.service.media.validateUserIdVerificationImage.maxSize')
    ],
    'validateAlertImage' => [
        'allowedMimes' => __('exceptions.service.media.validateAlertImage.allowedMimes'),
        'maxSize'      => __('exceptions.service.media.validateAlertImage.maxSize')
    ],
    'validateAlertSound' => [
        'allowedMimes' => __('exceptions.service.media.validateAlertSound.allowedMimes'),
        'maxSize'      => __('exceptions.service.media.validateAlertSound.maxSize')
    ],
    'validateVybeImage' => [
        'allowedMimes' => __('exceptions.service.media.validateVybeImage.allowedMimes'),
        'maxSize'      => __('exceptions.service.media.validateVybeImage.maxSize')
    ],
    'validateVybeVideo' => [
        'allowedMimes' => __('exceptions.service.media.validateVybeVideo.allowedMimes'),
        'maxSize'      => __('exceptions.service.media.validateVybeVideo.maxSize')
    ],
    'validateActivityImage' => [
        'allowedMimes' => __('exceptions.service.media.validateActivityImage.allowedMimes'),
        'maxSize'      => __('exceptions.service.media.validateActivityImage.maxSize')
    ]
];