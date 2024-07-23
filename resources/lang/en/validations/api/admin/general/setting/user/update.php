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

    'category' => [
        'required' => __('validations.api.admin.general.setting.user.update.category.required'),
        'string'   => __('validations.api.admin.general.setting.user.update.category.string'),
        'in'       => __('validations.api.admin.general.setting.user.update.category.in')
    ],
    'settings' => [
        'required' => __('validations.api.admin.general.setting.user.update.settings.required'),
        'array'    => __('validations.api.admin.general.setting.user.update.settings.array'),
        '*' => [
            'code' => [
                'required' => __('validations.api.admin.general.setting.user.update.settings.*.code.required'),
                'string'   => __('validations.api.admin.general.setting.user.update.settings.*.code.string')
            ],
            'fields' => [
                'required' => __('validations.api.admin.general.setting.user.update.settings.*.fields.required'),
                'array'    => __('validations.api.admin.general.setting.user.update.settings.*.fields.array'),
                '*' => [
                    'code' => [
                        'required' => __('validations.api.admin.general.setting.user.update.settings.*.code.required'),
                        'string'   => __('validations.api.admin.general.setting.user.update.settings.*.code.string')
                    ],
                    'value' => [
                        'required' => __('validations.api.admin.general.setting.user.update.settings.*.fields.*.value.required')
                    ]
                ]
            ]
        ]
    ],
    'result' => [
        'success' => __('validations.api.admin.general.setting.user.update.result.success')
    ]
];