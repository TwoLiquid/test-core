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

    'validateAppearanceCasesStatuses' => [
        'priceStatus'       => __('exceptions.service.vybe.vybeChangeRequest.validateAppearanceCasesStatuses.priceStatus'),
        'unitStatus'        => __('exceptions.service.vybe.vybeChangeRequest.validateAppearanceCasesStatuses.unitStatus'),
        'descriptionStatus' => __('exceptions.service.vybe.vybeChangeRequest.validateAppearanceCasesStatuses.descriptionStatus'),
        'platformsStatus'   => __('exceptions.service.vybe.vybeChangeRequest.validateAppearanceCasesStatuses.platformsStatus'),
        'locationStatus'    => __('exceptions.service.vybe.vybeChangeRequest.validateAppearanceCasesStatuses.locationStatus'),
        'csau' => [
            'unit' => [
                'pending' => __('exceptions.service.vybe.vybeChangeRequest.validateAppearanceCasesStatuses.csau.unit.pending')
            ],
            'city' => [
                'pending' => __('exceptions.service.vybe.vybeChangeRequest.validateAppearanceCasesStatuses.csau.city.pending')
            ]
        ]
    ],
    'checkImagesAreProcessed' => [
        'image' => __('exceptions.service.vybe.vybeChangeRequest.checkImagesAreProcessed.image')
    ],
    'checkVideosAreProcessed' => [
        'video' => __('exceptions.service.vybe.vybeChangeRequest.checkVideosAreProcessed.video')
    ]
];