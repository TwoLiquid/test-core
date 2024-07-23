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
        'required' => __('validations.api.admin.general.admin.role.store.name.required'),
        'string'   => __('validations.api.admin.general.admin.role.store.name.string'),
        'unique'   => __('validations.api.admin.general.admin.role.store.name.unique')
    ],
    'admins_ids' => [
        'array' => __('validations.api.admin.general.admin.role.store.admins_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.general.admin.role.store.admins_ids.*.required'),
            'integer'  => __('validations.api.admin.general.admin.role.store.admins_ids.*.integer'),
            'exists'   => __('validations.api.admin.general.admin.role.store.admins_ids.*.exists')
        ]
    ],
    'departments' => [
        'required' => __('validations.api.admin.general.admin.role.store.departments.required'),
        'array'    => __('validations.api.admin.general.admin.role.store.departments.array'),
        '*' => [
            'department_code' => [
                'required' => __('validations.api.admin.general.admin.role.store.departments.*.department_code.required'),
                'string'   => __('validations.api.admin.general.admin.role.store.departments.*.department_code.string'),
            ],
            'page_code' => [
                'required' => __('validations.api.admin.general.admin.role.store.departments.*.page_code.required'),
                'string'   => __('validations.api.admin.general.admin.role.store.departments.*.page_code.string'),
            ],
            'permissions' => [
                'required' => __('validations.api.admin.general.admin.role.store.departments.*.permissions.required'),
                'array'    => __('validations.api.admin.general.admin.role.store.departments.*.permissions.array'),
                '*' => [
                    'permission_id' => [
                        'required' => __('validations.api.admin.general.admin.role.store.departments.*.permissions.*.permission_id.required'),
                        'integer'  => __('validations.api.admin.general.admin.role.store.departments.*.permissions.*.permission_id.integer'),
                        'between'  => __('validations.api.admin.general.admin.role.store.departments.*.permissions.*.permission_id.between')
                    ],
                    'selected' => [
                        'required' => __('validations.api.admin.general.admin.role.store.departments.*.permissions.*.selected.required'),
                        'boolean'  => __('validations.api.admin.general.admin.role.store.departments.*.permissions.*.selected.boolean')
                    ]
                ]
            ]
        ]
    ],
    'result' => [
        'error' => [
            'create' => __('validations.api.admin.general.admin.role.store.result.error.create')
        ],
        'success' => __('validations.api.admin.general.admin.role.store.result.success')
    ]
];