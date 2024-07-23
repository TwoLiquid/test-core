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

    // 4-th step
    'deleted_images_ids' => [
        'array' => __('validations.api.general.vybe.step.updateFourthStepChanges.deleted_images_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.step.updateFourthStepChanges.deleted_images_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.step.updateFourthStepChanges.deleted_images_ids.*.integer')
        ]
    ],
    'deleted_videos_ids' => [
        'array' => __('validations.api.general.vybe.step.updateFourthStepChanges.deleted_videos_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.step.updateFourthStepChanges.deleted_videos_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.step.updateFourthStepChanges.deleted_videos_ids.*.integer')
        ]
    ],
    'files' => [
        'array' => __('validations.api.general.vybe.step.updateFourthStepChanges.files.array'),
        '*' => [
            'content' => [
                'required' => __('validations.api.general.vybe.step.updateFourthStepChanges.files.*.content.required'),
                'string'   => __('validations.api.general.vybe.step.updateFourthStepChanges.files.*.content.string')
            ],
            'extension' => [
                'required' => __('validations.api.general.vybe.step.updateFourthStepChanges.files.*.extension.required'),
                'string'   => __('validations.api.general.vybe.step.updateFourthStepChanges.files.*.extension.string')
            ],
            'mime' => [
                'required' => __('validations.api.general.vybe.step.updateFourthStepChanges.files.*.mime.required'),
                'string'   => __('validations.api.general.vybe.step.updateFourthStepChanges.files.*.mime.string')
            ],
            'original_name' => [
                'required' => __('validations.api.general.vybe.step.updateFourthStepChanges.files.*.original_name.required'),
                'string'   => __('validations.api.general.vybe.step.updateFourthStepChanges.files.*.original_name.string')
            ],
            'main' => [
                'boolean' => __('validations.api.general.vybe.step.updateFourthStepChanges.files.*.main.boolean')
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find'           => __('validations.api.general.vybe.step.updateFourthStepChanges.result.error.find'),
            'publishRequest' => __('validations.api.general.vybe.step.updateFourthStepChanges.result.error.publishRequest'),
            'owner'          => __('validations.api.general.vybe.step.updateFourthStepChanges.result.error.owner'),
            'completed'      => __('validations.api.general.vybe.step.updateFourthStepChanges.result.error.completed'),
            'files'          => [
                'many' => __('validations.api.general.vybe.step.updateFourthStepChanges.result.error.files.many')
            ]
        ],
        'success' => __('validations.api.general.vybe.step.updateFourthStepChanges.result.success')
    ]
];