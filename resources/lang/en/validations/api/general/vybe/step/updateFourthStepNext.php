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
        'array' => __('validations.api.general.vybe.step.updateFourthStepNext.deleted_images_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.step.updateFourthStepNext.deleted_images_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.step.updateFourthStepNext.deleted_images_ids.*.integer')
        ]
    ],
    'deleted_videos_ids' => [
        'array' => __('validations.api.general.vybe.step.updateFourthStepNext.deleted_videos_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.step.updateFourthStepNext.deleted_videos_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.step.updateFourthStepNext.deleted_videos_ids.*.integer')
        ]
    ],
    'files' => [
        'array' => __('validations.api.general.vybe.step.updateFourthStepNext.files.array'),
        '*' => [
            'content' => [
                'required' => __('validations.api.general.vybe.step.updateFourthStepNext.files.*.content.required'),
                'string'   => __('validations.api.general.vybe.step.updateFourthStepNext.files.*.content.string')
            ],
            'extension' => [
                'required' => __('validations.api.general.vybe.step.updateFourthStepNext.files.*.extension.required'),
                'string'   => __('validations.api.general.vybe.step.updateFourthStepNext.files.*.extension.string')
            ],
            'mime' => [
                'required' => __('validations.api.general.vybe.step.updateFourthStepNext.files.*.mime.required'),
                'string'   => __('validations.api.general.vybe.step.updateFourthStepNext.files.*.mime.string')
            ],
            'original_name' => [
                'required' => __('validations.api.general.vybe.step.updateFourthStepNext.files.*.original_name.required'),
                'string'   => __('validations.api.general.vybe.step.updateFourthStepNext.files.*.original_name.string')
            ],
            'main' => [
                'boolean' => __('validations.api.general.vybe.step.updateFourthStepNext.files.*.main.boolean')
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find'           => __('validations.api.general.vybe.step.updateFourthStepNext.result.error.find'),
            'publishRequest' => __('validations.api.general.vybe.step.updateFourthStepNext.result.error.publishRequest'),
            'owner'          => __('validations.api.general.vybe.step.updateFourthStepNext.result.error.owner'),
            'completed'      => __('validations.api.general.vybe.step.updateFourthStepNext.result.error.completed'),
            'stepForward'    => __('validations.api.general.vybe.step.updateFourthStepNext.result.error.stepForward'),
            'files'          => [
                'many'    => __('validations.api.general.vybe.step.updateFourthStepNext.result.error.files.many'),
                'absence' => __('validations.api.general.vybe.step.updateFourthStepNext.result.error.files.absence')
            ]
        ],
        'success' => __('validations.api.general.vybe.step.updateFourthStepNext.result.success')
    ]
];