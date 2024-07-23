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
        'required' => __('validations.api.admin.general.setting.vybe.update.settings.required'),
        'array'    => __('validations.api.admin.general.setting.vybe.update.settings.array'),
        '*' => [
            'code' => [
                'required' => __('validations.api.admin.general.setting.vybe.update.settings.*.code.required'),
                'string'   => __('validations.api.admin.general.setting.vybe.update.settings.*.code.string')
            ],
            'fields' => [
                'required' => __('validations.api.admin.general.setting.vybe.update.settings.*.fields.required'),
                'array'    => __('validations.api.admin.general.setting.vybe.update.settings.*.fields.array'),
                '*' => [
                    'code' => [
                        'required' => __('validations.api.admin.general.setting.vybe.update.settings.*.code.required'),
                        'string'   => __('validations.api.admin.general.setting.vybe.update.settings.*.code.string')
                    ],
                    'value' => [
                        'required' => __('validations.api.admin.general.setting.vybe.update.settings.*.fields.*.value.required')
                    ]
                ]
            ]
        ]
    ],
    'result' => [
        'success' => __('validations.api.admin.general.setting.vybe.update.result.success')
    ]
];