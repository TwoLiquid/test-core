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

    'validateAppearanceCases' => [
        'unit' => [
            'absence'  => __('exceptions.service.vybe.vybe.validateAppearanceCases.unit.absence'),
            'doubling' => __('exceptions.service.vybe.vybe.validateAppearanceCases.unit.doubling')
        ],
        'appearance'   => __('exceptions.service.vybe.vybe.validateAppearanceCases.appearance'),
        'platforms'    => __('exceptions.service.vybe.vybe.validateAppearanceCases.platforms'),
        'city' => [
            'absence'  => __('exceptions.service.vybe.vybe.validateAppearanceCases.city.absence')
        ]
    ],
    'validateSchedules' => [
        'event' => [
            'one' => __('exceptions.service.vybe.vybe.validateSchedules.event.one')
        ],
        'datetime_from' => [
            'find'    => __('exceptions.service.vybe.vybe.validateSchedules.datetime_from.find'),
            'invalid' => __('exceptions.service.vybe.vybe.validateSchedules.datetime_from.invalid')
        ],
        'datetime_to' => [
            'find'    => __('exceptions.service.vybe.vybe.validateSchedules.datetime_to.find'),
            'invalid' => __('exceptions.service.vybe.vybe.validateSchedules.datetime_to.invalid')
        ]
    ],
    'validateFiles' => [
        'files' => [
            'many'      => __('exceptions.service.vybe.vybe.validateFiles.files.many'),
            'undefined' => __('exceptions.service.vybe.vybe.validateFiles.files.undefined')
        ]
    ],
    'checkFilesUploadAvailability' => [
        'files' => [
            'many'    => __('exceptions.service.vybe.vybe.validateFiles.files.many'),
            'absence' => __('exceptions.service.vybe.vybe.checkFilesUploadAvailability.files.absence')
        ]
    ]
];