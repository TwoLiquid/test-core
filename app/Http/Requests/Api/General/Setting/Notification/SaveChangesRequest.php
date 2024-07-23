<?php

namespace App\Http\Requests\Api\General\Setting\Notification;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class SaveChangesRequest
 *
 * @package App\Http\Requests\Api\General\Setting\Notification
 */
class SaveChangesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'notification_enable'          => 'required|boolean',
            'email_followers_follows_you'  => 'required|boolean',
            'email_followers_new_vybe'     => 'required|boolean',
            'email_followers_new_event'    => 'required|boolean',
            'messages_unread'              => 'required|boolean',
            'email_orders_new'             => 'required|boolean',
            'email_order_starts'           => 'required|boolean',
            'email_order_pending'          => 'required|boolean',
            'reschedule_info'              => 'required|boolean',
            'review_new'                   => 'required|boolean',
            'review_waiting'               => 'required|boolean',
            'withdrawals_info'             => 'required|boolean',
            'email_invitation_info'        => 'required|boolean',
            'tickets_new_order'            => 'required|boolean',
            'miscellaneous_regarding'      => 'required|boolean',
            'platform_followers_follows'   => 'required|boolean',
            'platform_followers_new_vybe'  => 'required|boolean',
            'platform_followers_new_event' => 'required|boolean',
            'platform_order_starts'        => 'required|boolean',
            'platform_invitation_info'     => 'required|boolean',
            'news_receive'                 => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'notification_enable.required'          => trans('validations/api/general/setting/notification/saveChanges.notification_enable.required'),
            'notification_enable.boolean'           => trans('validations/api/general/setting/notification/saveChanges.notification_enable.boolean'),
            'email_followers_follows_you.required'  => trans('validations/api/general/setting/notification/saveChanges.email_followers_follows_you.required'),
            'email_followers_follows_you.boolean'   => trans('validations/api/general/setting/notification/saveChanges.email_followers_follows_you.boolean'),
            'email_followers_new_vybe.required'     => trans('validations/api/general/setting/notification/saveChanges.email_followers_new_vybe.required'),
            'email_followers_new_vybe.boolean'      => trans('validations/api/general/setting/notification/saveChanges.email_followers_new_vybe.boolean'),
            'email_followers_new_event.required'    => trans('validations/api/general/setting/notification/saveChanges.email_followers_new_event.required'),
            'email_followers_new_event.boolean'     => trans('validations/api/general/setting/notification/saveChanges.email_followers_new_event.boolean'),
            'messages_unread.required'              => trans('validations/api/general/setting/notification/saveChanges.messages_unread.required'),
            'messages_unread.boolean'               => trans('validations/api/general/setting/notification/saveChanges.messages_unread.boolean'),
            'email_orders_new.required'             => trans('validations/api/general/setting/notification/saveChanges.email_orders_new.required'),
            'email_orders_new.boolean'              => trans('validations/api/general/setting/notification/saveChanges.email_orders_new.boolean'),
            'email_order_starts.required'           => trans('validations/api/general/setting/notification/saveChanges.email_order_starts.required'),
            'email_order_starts.boolean'            => trans('validations/api/general/setting/notification/saveChanges.email_order_starts.boolean'),
            'email_order_pending.required'          => trans('validations/api/general/setting/notification/saveChanges.email_order_pending.required'),
            'email_order_pending.boolean'           => trans('validations/api/general/setting/notification/saveChanges.email_order_pending.boolean'),
            'reschedule_info.required'              => trans('validations/api/general/setting/notification/saveChanges.reschedule_info.required'),
            'reschedule_info.boolean'               => trans('validations/api/general/setting/notification/saveChanges.reschedule_info.boolean'),
            'review_new.required'                   => trans('validations/api/general/setting/notification/saveChanges.review_new.required'),
            'review_new.boolean'                    => trans('validations/api/general/setting/notification/saveChanges.review_new.boolean'),
            'review_waiting.required'               => trans('validations/api/general/setting/notification/saveChanges.review_waiting.required'),
            'review_waiting.boolean'                => trans('validations/api/general/setting/notification/saveChanges.review_waiting.boolean'),
            'withdrawals_info.required'             => trans('validations/api/general/setting/notification/saveChanges.withdrawals_info.required'),
            'withdrawals_info.boolean'              => trans('validations/api/general/setting/notification/saveChanges.withdrawals_info.boolean'),
            'email_invitation_info.required'        => trans('validations/api/general/setting/notification/saveChanges.email_invitation_info.required'),
            'email_invitation_info.boolean'         => trans('validations/api/general/setting/notification/saveChanges.email_invitation_info.boolean'),
            'tickets_new_order.required'            => trans('validations/api/general/setting/notification/saveChanges.tickets_new_order.required'),
            'tickets_new_order.boolean'             => trans('validations/api/general/setting/notification/saveChanges.tickets_new_order.boolean'),
            'miscellaneous_regarding.required'      => trans('validations/api/general/setting/notification/saveChanges.miscellaneous_regarding.required'),
            'miscellaneous_regarding.boolean'       => trans('validations/api/general/setting/notification/saveChanges.miscellaneous_regarding.boolean'),
            'platform_followers_follows.required'   => trans('validations/api/general/setting/notification/saveChanges.platform_followers_follows.required'),
            'platform_followers_follows.boolean'    => trans('validations/api/general/setting/notification/saveChanges.platform_followers_follows.boolean'),
            'platform_followers_new_vybe.required'  => trans('validations/api/general/setting/notification/saveChanges.platform_followers_new_vybe.required'),
            'platform_followers_new_vybe.boolean'   => trans('validations/api/general/setting/notification/saveChanges.platform_followers_new_vybe.boolean'),
            'platform_followers_new_event.required' => trans('validations/api/general/setting/notification/saveChanges.platform_followers_new_event.required'),
            'platform_followers_new_event.boolean'  => trans('validations/api/general/setting/notification/saveChanges.platform_followers_new_event.boolean'),
            'platform_order_starts.required'        => trans('validations/api/general/setting/notification/saveChanges.platform_order_starts.required'),
            'platform_order_starts.boolean'         => trans('validations/api/general/setting/notification/saveChanges.platform_order_starts.boolean'),
            'platform_invitation_info.required'     => trans('validations/api/general/setting/notification/saveChanges.platform_invitation_info.required'),
            'platform_invitation_info.boolean'      => trans('validations/api/general/setting/notification/saveChanges.platform_invitation_info.boolean'),
            'news_receive.required'                 => trans('validations/api/general/setting/notification/saveChanges.news_receive.required'),
            'news_receive.boolean'                  => trans('validations/api/general/setting/notification/saveChanges.news_receive.boolean')
        ];
    }
}
