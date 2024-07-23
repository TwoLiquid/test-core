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

    'title_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.title_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.title_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.title_status_id.in')
    ],
    'category_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.category_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.category_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.category_status_id.in')
    ],
    'subcategory_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.subcategory_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.subcategory_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.subcategory_status_id.in')
    ],
    'devices_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.devices_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.devices_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.devices_status_id.in')
    ],
    'activity_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.activity_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.activity_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.activity_status_id.in')
    ],
    'period_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.period_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.period_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.period_status_id.in')
    ],
    'user_count_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.title_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.user_count_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.user_count_status_id.in')
    ],
    'appearance_cases' => [
        'voice_chat' => [
            'array' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.array'),
            'price_status_id' => [
                'required' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.price_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.price_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.video_chat.price_status_id.in')
            ],
            'unit_status_id' => [
                'required' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.unit_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.unit_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.video_chat.unit_status_id.in')
            ],
            'description_status_id' => [
                'required' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.description_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.description_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.video_chat.description_status_id.in')
            ],
            'platforms_status_id' => [
                'integer' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.platforms_status_id.integer'),
                'in'      => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.video_chat.platforms_status_id.in')
            ],
            'enabled' => [
                'required' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'video_chat' => [
            'array' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.video_chat.array'),
            'price_status_id' => [
                'required' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.price_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.price_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.video_chat.price_status_id.in')
            ],
            'unit_status_id' => [
                'required' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.unit_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.unit_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.video_chat.unit_status_id.in')
            ],
            'description_status_id' => [
                'required' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.description_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.description_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.video_chat.description_status_id.in')
            ],
            'platforms_status_id' => [
                'integer' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.platforms_status_id.integer'),
                'in'      => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.video_chat.platforms_status_id.in')
            ],
            'enabled' => [
                'required' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'real_life' => [
            'array' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.real_life.array'),
            'price_status_id' => [
                'required' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.price_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.price_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.video_chat.price_status_id.in')
            ],
            'unit_status_id' => [
                'required' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.unit_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.unit_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.video_chat.unit_status_id.in')
            ],
            'description_status_id' => [
                'required' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.description_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.description_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.video_chat.description_status_id.in')
            ],
            'location_status_id' => [
                'integer' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.real_life.location_status_id.integer'),
                'in'      => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.real_life.location_status_id.in')
            ],
            'enabled' => [
                'required' => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.admin.vybe.publishRequest.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ]
    ],
    'schedules_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.schedules_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.schedules_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.schedules_status_id.in')
    ],
    'order_advance_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.order_advance_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.order_advance_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.order_advance_status_id.in')
    ],
    'declined_images_ids' => [
        'array' => __('validations.api.admin.vybe.publishRequest.update.declined_images_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.vybe.publishRequest.update.declined_images_ids.*.required'),
            'integer'  => __('validations.api.admin.vybe.publishRequest.update.declined_images_ids.*.integer')
        ]
    ],
    'declined_videos_ids' => [
        'array' => __('validations.api.admin.vybe.publishRequest.update.declined_videos_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.vybe.publishRequest.update.declined_videos_ids.*.required'),
            'integer'  => __('validations.api.admin.vybe.publishRequest.update.declined_videos_ids.*.integer')
        ]
    ],
    'access_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.access_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.access_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.access_status_id.in')
    ],
    'showcase_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.showcase_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.showcase_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.showcase_status_id.integer')
    ],
    'order_accept_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.order_accept_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.order_accept_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.order_accept_status_id.in')
    ],
    'age_limit_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.age_limit_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.age_limit_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.age_limit_id.in')
    ],
    'status_status_id' => [
        'required' => __('validations.api.admin.vybe.publishRequest.update.status_status_id.required'),
        'integer'  => __('validations.api.admin.vybe.publishRequest.update.status_status_id.integer'),
        'in'       => __('validations.api.admin.vybe.publishRequest.update.status_status_id.in')
    ],
    'toast_message_text' => [
        'boolean' => __('validations.api.admin.vybe.publishRequest.update.toast_message_text.boolean')
    ],
    'result' => [
        'error' => [
            'find' => [
                'vybePublishRequest' => __('validations.api.admin.vybe.publishRequest.update.result.error.find.vybePublishRequest'),
                'titleStatus'        => __('validations.api.admin.vybe.publishRequest.update.result.error.find.titleStatus'),
                'categoryStatus'     => __('validations.api.admin.vybe.publishRequest.update.result.error.find.categoryStatus'),
                'devicesStatus'      => __('validations.api.admin.vybe.publishRequest.update.result.error.find.devicesStatus'),
                'activityStatus'     => __('validations.api.admin.vybe.publishRequest.update.result.error.find.activityStatus'),
                'periodStatus'       => __('validations.api.admin.vybe.publishRequest.update.result.error.find.periodStatus'),
                'userCountStatus'    => __('validations.api.admin.vybe.publishRequest.update.result.error.find.userCountStatus'),
                'orderAdvanceStatus' => __('validations.api.admin.vybe.publishRequest.update.result.error.find.orderAdvanceStatus'),
                'accessStatus'       => __('validations.api.admin.vybe.publishRequest.update.result.error.find.accessStatus'),
                'showcaseStatus'     => __('validations.api.admin.vybe.publishRequest.update.result.error.find.showcaseStatus'),
                'orderAcceptStatus'  => __('validations.api.admin.vybe.publishRequest.update.result.error.find.orderAcceptStatus'),
                'statusStatus'       => __('validations.api.admin.vybe.publishRequest.update.result.error.find.statusStatus')
            ],
            'csau' => [
                'category'    => __('validations.api.admin.vybe.publishRequest.update.result.error.csau.category'),
                'subcategory' => __('validations.api.admin.vybe.publishRequest.update.result.error.csau.subcategory'),
                'device'      => __('validations.api.admin.vybe.publishRequest.update.result.error.csau.device'),
                'activity'    => __('validations.api.admin.vybe.publishRequest.update.result.error.csau.activity'),
            ],
            'pending' => __('validations.api.admin.vybe.publishRequest.update.result.error.pending')
        ],
        'success' => __('validations.api.admin.vybe.publishRequest.update.result.success')
    ]
];
