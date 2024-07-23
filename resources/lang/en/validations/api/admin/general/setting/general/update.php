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
        'required' => __('validations.api.admin.general.setting.general.update.settings.required'),
        'array'    => __('validations.api.admin.general.setting.general.update.settings.array'),
        '*' => [
            'code' => [
                'required' => __('validations.api.admin.general.setting.general.update.settings.*.code.required'),
                'string'   => __('validations.api.admin.general.setting.general.update.settings.*.code.string')
            ],
            'fields' => [
                'required' => __('validations.api.admin.general.setting.general.update.settings.*.fields.required'),
                'array'    => __('validations.api.admin.general.setting.general.update.settings.*.fields.array'),
                '*' => [
                    'code' => [
                        'required' => __('validations.api.admin.general.setting.general.update.settings.*.code.required'),
                        'string'   => __('validations.api.admin.general.setting.general.update.settings.*.code.string')
                    ]
                ]
            ]
        ]
    ],
    'result' => [
        'success' => __('validations.api.admin.general.setting.general.update.result.success')
    ]
];