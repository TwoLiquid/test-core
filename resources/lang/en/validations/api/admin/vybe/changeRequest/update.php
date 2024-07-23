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
        'integer' => __('validations.api.admin.vybe.changeRequest.update.title_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.title_status_id.in')
    ],
    'category_status_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.category_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.category_status_id.in')
    ],
    'subcategory_status_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.subcategory_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.subcategory_status_id.in')
    ],
    'devices_status_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.devices_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.devices_status_id.in')
    ],
    'activity_status_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.activity_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.activity_status_id.in')
    ],
    'period_status_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.period_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.period_status_id.in')
    ],
    'user_count_status_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.user_count_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.user_count_status_id.in')
    ],
    'appearance_cases' => [
        'voice_chat' => [
            'array' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.array'),
            'price_status_id' => [
                'required' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.price_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.price_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.video_chat.price_status_id.in')
            ],
            'unit_status_id' => [
                'required' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.unit_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.unit_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.video_chat.unit_status_id.in')
            ],
            'description_status_id' => [
                'required' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.description_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.description_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.video_chat.description_status_id.in')
            ],
            'platforms_status_id' => [
                'integer' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.platforms_status_id.integer'),
                'in'      => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.video_chat.platforms_status_id.in')
            ],
            'enabled' => [
                'required' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'video_chat' => [
            'array' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.video_chat.array'),
            'price_status_id' => [
                'required' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.price_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.price_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.video_chat.price_status_id.in')
            ],
            'unit_status_id' => [
                'required' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.unit_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.unit_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.video_chat.unit_status_id.in')
            ],
            'description_status_id' => [
                'required' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.description_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.description_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.video_chat.description_status_id.in')
            ],
            'platforms_status_id' => [
                'integer' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.platforms_status_id.integer'),
                'in'      => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.video_chat.platforms_status_id.in')
            ],
            'enabled' => [
                'required' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'real_life' => [
            'array' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.real_life.array'),
            'price_status_id' => [
                'required' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.price_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.price_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.video_chat.price_status_id.in')
            ],
            'unit_status_id' => [
                'required' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.unit_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.unit_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.video_chat.unit_status_id.in')
            ],
            'description_status_id' => [
                'required' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.description_status_id.required'),
                'integer'  => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.description_status_id.integer'),
                'in'       => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.video_chat.description_status_id.in')
            ],
            'location_status_id' => [
                'integer' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.real_life.location_status_id.integer'),
                'in'      => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.real_life.location_status_id.in')
            ],
            'enabled' => [
                'required' => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.admin.vybe.changeRequest.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ]
    ],
    'schedules_status_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.schedules_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.schedules_status_id.in')
    ],
    'order_advance_status_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.order_advance_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.order_advance_status_id.in')
    ],
    'accepted_images_ids' => [
        'array' => __('validations.api.admin.vybe.changeRequest.update.accepted_images_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.vybe.changeRequest.update.accepted_images_ids.*.required'),
            'integer'  => __('validations.api.admin.vybe.changeRequest.update.accepted_images_ids.*.integer')
        ]
    ],
    'accepted_videos_ids' => [
        'array' => __('validations.api.admin.vybe.changeRequest.update.accepted_videos_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.vybe.changeRequest.update.accepted_videos_ids.*.required'),
            'integer'  => __('validations.api.admin.vybe.changeRequest.update.accepted_videos_ids.*.integer')
        ]
    ],
    'declined_images_ids' => [
        'array' => __('validations.api.admin.vybe.changeRequest.update.declined_images_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.vybe.changeRequest.update.declined_images_ids.*.required'),
            'integer'  => __('validations.api.admin.vybe.changeRequest.update.declined_images_ids.*.integer')
        ]
    ],
    'declined_videos_ids' => [
        'array' => __('validations.api.admin.vybe.changeRequest.update.declined_videos_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.vybe.changeRequest.update.declined_videos_ids.*.required'),
            'integer'  => __('validations.api.admin.vybe.changeRequest.update.declined_videos_ids.*.integer')
        ]
    ],
    'access_status_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.access_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.access_status_id.in')
    ],
    'showcase_status_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.showcase_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.showcase_status_id.in')
    ],
    'order_accept_status_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.order_accept_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.order_accept_status_id.in')
    ],
    'age_limit_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.age_limit_id.integer')
    ],
    'status_status_id' => [
        'integer' => __('validations.api.admin.vybe.changeRequest.update.status_status_id.integer'),
        'in'      => __('validations.api.admin.vybe.changeRequest.update.status_status_id.in')
    ],
    'toast_message_text' => [
        'boolean' => __('validations.api.admin.vybe.changeRequest.update.toast_message_text.boolean')
    ],
    'result' => [
        'error' => [
            'find' => [
                'vybeChangeRequest'  => __('validations.api.admin.vybe.changeRequest.update.result.error.find.vybeChangeRequest'),
                'titleStatus'        => __('validations.api.admin.vybe.changeRequest.update.result.error.find.titleStatus'),
                'categoryStatus'     => __('validations.api.admin.vybe.changeRequest.update.result.error.find.categoryStatus'),
                'devicesStatus'      => __('validations.api.admin.vybe.changeRequest.update.result.error.find.devicesStatus'),
                'activityStatus'     => __('validations.api.admin.vybe.changeRequest.update.result.error.find.activityStatus'),
                'periodStatus'       => __('validations.api.admin.vybe.changeRequest.update.result.error.find.periodStatus'),
                'userCountStatus'    => __('validations.api.admin.vybe.changeRequest.update.result.error.find.userCountStatus'),
                'orderAdvanceStatus' => __('validations.api.admin.vybe.changeRequest.update.result.error.find.orderAdvanceStatus'),
                'accessStatus'       => __('validations.api.admin.vybe.changeRequest.update.result.error.find.accessStatus'),
                'showcaseStatus'     => __('validations.api.admin.vybe.changeRequest.update.result.error.find.showcaseStatus'),
                'orderAcceptStatus'  => __('validations.api.admin.vybe.changeRequest.update.result.error.find.orderAcceptStatus'),
                'statusStatus'       => __('validations.api.admin.vybe.changeRequest.update.result.error.find.statusStatus')
            ],
            'csau' => [
                'category'    => __('validations.api.admin.vybe.changeRequest.update.result.error.csau.category'),
                'subcategory' => __('validations.api.admin.vybe.changeRequest.update.result.error.csau.subcategory'),
                'device'      => __('validations.api.admin.vybe.changeRequest.update.result.error.csau.device'),
                'activity'    => __('validations.api.admin.vybe.changeRequest.update.result.error.csau.activity'),
            ],
            'pending' => __('validations.api.admin.vybe.changeRequest.update.result.error.pending')
        ],
        'success' => __('validations.api.admin.vybe.changeRequest.update.result.success')
    ]
];
