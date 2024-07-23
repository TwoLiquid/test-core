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

    'settings' => [
        'required' => __('validations.api.admin.general.setting.request.update.settings.required'),
        'array'    => __('validations.api.admin.general.setting.request.update.settings.array'),
        '*' => [
            'code' => [
                'required' => __('validations.api.admin.general.setting.request.update.settings.*.code.required'),
                'string'   => __('validations.api.admin.general.setting.request.update.settings.*.code.string')
            ],
            'fields' => [
                'required' => __('validations.api.admin.general.setting.request.update.settings.*.fields.required'),
                'array'    => __('validations.api.admin.general.setting.request.update.settings.*.fields.array'),
                '*' => [
                    'code' => [
                        'required' => __('validations.api.admin.general.setting.request.update.settings.*.code.required'),
                        'string'   => __('validations.api.admin.general.setting.request.update.settings.*.code.string')
                    ],
                    'value' => [
                        'required' => __('validations.api.admin.general.setting.request.update.settings.*.fields.*.value.required')
                    ]
                ]
            ]
        ]
    ],
    'result' => [
        'success' => __('validations.api.admin.general.setting.request.update.result.success')
    ]
];