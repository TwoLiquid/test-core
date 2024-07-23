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

    'notification_enable' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.notification_enable.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.notification_enable.boolean')
    ],
    'email_followers_follows_you' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.email_followers_follows_you.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.email_followers_follows_you.boolean')
    ],
    'email_followers_new_vybe' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.email_followers_new_vybe.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.email_followers_new_vybe.boolean')
    ],
    'email_followers_new_event' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.email_followers_new_event.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.email_followers_new_event.boolean')
    ],
    'messages_unread' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.messages_unread.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.messages_unread.boolean')
    ],
    'email_orders_new' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.email_orders_new.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.email_orders_new.boolean')
    ],
    'email_order_starts' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.email_order_starts.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.email_order_starts.boolean')
    ],
    'email_order_pending' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.email_order_pending.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.email_order_pending.boolean')
    ],
    'reschedule_info' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.reschedule_info.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.reschedule_info.boolean')
    ],
    'review_new' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.review_new.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.review_new.boolean')
    ],
    'review_waiting' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.review_waiting.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.review_waiting.boolean')
    ],
    'withdrawals_info' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.withdrawals_info.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.withdrawals_info.boolean')
    ],
    'email_invitation_info' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.email_invitation_info.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.email_invitation_info.boolean')
    ],
    'tickets_new_order' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.tickets_new_order.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.tickets_new_order.boolean')
    ],
    'miscellaneous_regarding' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.miscellaneous_regarding.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.miscellaneous_regarding.boolean')
    ],
    'platform_followers_follows' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.platform_followers_follows.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.platform_followers_follows.boolean')
    ],
    'platform_followers_new_vybe' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.platform_followers_new_vybe.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.platform_followers_new_vybe.boolean')
    ],
    'platform_followers_new_event' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.platform_followers_new_event.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.platform_followers_new_event.boolean')
    ],
    'platform_order_starts' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.platform_order_starts.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.platform_order_starts.boolean')
    ],
    'platform_invitation_info' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.platform_invitation_info.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.platform_invitation_info.boolean')
    ],
    'news_receive' => [
        'required' => __('validations.api.general.setting.notification.saveChanges.news_receive.required'),
        'boolean'  => __('validations.api.general.setting.notification.saveChanges.news_receive.boolean')
    ],
    'result' => [
        'error' => [
            'exists' => __('validations.api.general.setting.notification.saveChanges.result.error.exists'),
        ],
        'success' => __('validations.api.general.setting.notification.saveChanges.result.success')
    ]
];