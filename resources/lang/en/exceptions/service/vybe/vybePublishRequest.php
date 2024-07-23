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

    'executePublishRequestForVybe' => [
        'create' => __('exceptions.service.vybe.vybePublishRequest.executePublishRequestForVybe.create')
    ],
    'validateAppearanceCasesStatuses' => [
        'priceStatus'       => __('exceptions.service.vybe.vybePublishRequest.validateAppearanceCasesStatuses.priceStatus'),
        'unitStatus'        => __('exceptions.service.vybe.vybePublishRequest.validateAppearanceCasesStatuses.unitStatus'),
        'descriptionStatus' => __('exceptions.service.vybe.vybePublishRequest.validateAppearanceCasesStatuses.descriptionStatus'),
        'platformsStatus'   => __('exceptions.service.vybe.vybePublishRequest.validateAppearanceCasesStatuses.platformsStatus'),
        'locationStatus'    => __('exceptions.service.vybe.vybePublishRequest.validateAppearanceCasesStatuses.locationStatus'),
        'csau' => [
            'unit' => [
                'pending' => __('exceptions.service.vybe.vybePublishRequest.validateAppearanceCasesStatuses.csau.unit.pending')
            ],
            'city' => [
                'pending' => __('exceptions.service.vybe.vybePublishRequest.validateAppearanceCasesStatuses.csau.city.pending')
            ]
        ]
    ],
    'checkImagesAreProcessed' => [
        'image' => __('exceptions.service.vybe.vybePublishRequest.checkImagesAreProcessed.image')
    ],
    'checkVideosAreProcessed' => [
        'video' => __('exceptions.service.vybe.vybePublishRequest.checkVideosAreProcessed.video')
    ]
];