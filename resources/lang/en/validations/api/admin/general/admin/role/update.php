<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Admin Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'name' => [
        'required' => __('validations.api.admin.general.admin.role.update.name.required'),
        'string'   => __('validations.api.admin.general.admin.role.update.name.string'),
        'unique'   => __('validations.api.admin.general.admin.role.update.name.unique')
    ],
    'admins_ids' => [
        'array' => __('validations.api.admin.general.admin.role.update.admins_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.general.admin.role.update.admins_ids.*.required'),
            'integer'  => __('validations.api.admin.general.admin.role.update.admins_ids.*.integer'),
            'exists'   => __('validations.api.admin.general.admin.role.update.admins_ids.*.exists')
        ]
    ],
    'departments' => [
        'required' => __('validations.api.admin.general.admin.role.update.departments.required'),
        'array'    => __('validations.api.admin.general.admin.role.update.departments.array'),
        '*' => [
            'department_code' => [
                'required' => __('validations.api.admin.general.admin.role.update.departments.*.department_code.required'),
                'string'   => __('validations.api.admin.general.admin.role.update.departments.*.department_code.string'),
            ],
            'page_code' => [
                'required' => __('validations.api.admin.general.admin.role.update.departments.*.page_code.required'),
                'string'   => __('validations.api.admin.general.admin.role.update.departments.*.page_code.string'),
            ],
            'permissions' => [
                'required' => __('validations.api.admin.general.admin.role.update.departments.*.permissions.required'),
                'array'    => __('validations.api.admin.general.admin.role.update.departments.*.permissions.array'),
                '*' => [
                    'permission_id' => [
                        'required' => __('validations.api.admin.general.admin.role.update.departments.*.permissions.*.permission_id.required'),
                        'integer'  => __('validations.api.admin.general.admin.role.update.departments.*.permissions.*.permission_id.integer'),
                        'between'  => __('validations.api.admin.general.admin.role.update.departments.*.permissions.*.permission_id.between')
                    ],
                    'selected' => [
                        'required' => __('validations.api.admin.general.admin.role.update.departments.*.permissions.*.selected.required'),
                        'boolean'  => __('validations.api.admin.general.admin.role.update.departments.*.permissions.*.selected.boolean')
                    ]
                ]
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.general.admin.role.update.result.error.find')
        ],
        'success' => __('validations.api.admin.general.admin.role.update.result.success')
    ]
];