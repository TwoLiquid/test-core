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

    'ip_ban_list_ids' => [
        'array' => __('validations.api.admin.general.ipBanList.destroyMany.ip_ban_list_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.general.ipBanList.destroyMany.ip_ban_list_ids.*.required'),
            'integer'  => __('validations.api.admin.general.ipBanList.destroyMany.ip_ban_list_ids.*.integer'),
            'exists'   => __('validations.api.admin.general.ipBanList.destroyMany.ip_ban_list_ids.*.exists')
        ]
    ],
    'result' => [
        'error' => [
            'delete' => __('validations.api.admin.general.ipBanList.destroyMany.result.error.delete')
        ],
        'success' => __('validations.api.admin.general.ipBanList.destroyMany.result.success')
    ]
];