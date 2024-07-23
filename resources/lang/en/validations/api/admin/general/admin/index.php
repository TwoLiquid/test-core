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

    'name' => [
        'string' => __('validations.api.admin.general.admin.index.name.string')
    ],
    'email' => [
        'regex' => __('validations.api.admin.general.admin.index.email.regex')
    ],
    'role_id' => [
        'integer' => __('validations.api.admin.general.admin.index.role_id.integer'),
        'exists'  => __('validations.api.admin.general.admin.index.role_id.exists')
    ],
    'status_id' => [
        'integer' => __('validations.api.admin.general.admin.index.role_id.integer'),
        'in'      => __('validations.api.admin.general.admin.index.role_id.exists')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.general.admin.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.general.admin.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.general.admin.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.general.admin.index.result.success')
    ]
];