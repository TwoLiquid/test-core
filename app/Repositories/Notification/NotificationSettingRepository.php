<?php

namespace App\Repositories\Notification;

use App\Exceptions\DatabaseException;
use App\Models\MySql\NotificationSetting;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Notification\Interfaces\NotificationSettingRepositoryInterface;
use Exception;

/**
 * Class NotificationSettingRepository
 *
 * @package App\Repositories\Notification
 */
class NotificationSettingRepository extends BaseRepository implements NotificationSettingRepositoryInterface
{
    /**
     * NotificationSettingRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.notificationSetting.perPage');
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsForUser(
        User $user
    ) : bool
    {
        try {
            return $user->notificationSettings()
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/notificationSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param bool $notificationEnable
     * @param bool $emailFollowersFollowsYou
     * @param bool $emailFollowersNewVybe
     * @param bool $emailFollowersNewEvent
     * @param bool $messagesUnread
     * @param bool $emailOrdersNew
     * @param bool $emailOrderStarts
     * @param bool $emailOrderPending
     * @param bool $rescheduleInfo
     * @param bool $reviewNew
     * @param bool $reviewWaiting
     * @param bool $withdrawalsInfo
     * @param bool $emailInvitationInfo
     * @param bool $ticketsNewOrder
     * @param bool $miscellaneousRegarding
     * @param bool $platformFollowersFollows
     * @param bool $platformFollowersNewVybe
     * @param bool $platformFollowersNewEvent
     * @param bool $platformOrderStarts
     * @param bool $platformInvitationInfo
     * @param bool $newsReceive
     *
     * @return NotificationSetting|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        bool $notificationEnable,
        bool $emailFollowersFollowsYou,
        bool $emailFollowersNewVybe,
        bool $emailFollowersNewEvent,
        bool $messagesUnread,
        bool $emailOrdersNew,
        bool $emailOrderStarts,
        bool $emailOrderPending,
        bool $rescheduleInfo,
        bool $reviewNew,
        bool $reviewWaiting,
        bool $withdrawalsInfo,
        bool $emailInvitationInfo,
        bool $ticketsNewOrder,
        bool $miscellaneousRegarding,
        bool $platformFollowersFollows,
        bool $platformFollowersNewVybe,
        bool $platformFollowersNewEvent,
        bool $platformOrderStarts,
        bool $platformInvitationInfo,
        bool $newsReceive
    ) : ?NotificationSetting
    {
        try {
            return NotificationSetting::query()->create([
                'user_id'                      => $user->id,
                'notification_enable'          => $notificationEnable,
                'email_followers_follows_you'  => $emailFollowersFollowsYou,
                'email_followers_new_vybe'     => $emailFollowersNewVybe,
                'email_followers_new_event'    => $emailFollowersNewEvent,
                'messages_unread'              => $messagesUnread,
                'email_orders_new'             => $emailOrdersNew,
                'email_order_starts'           => $emailOrderStarts,
                'email_order_pending'          => $emailOrderPending,
                'reschedule_info'              => $rescheduleInfo,
                'review_new'                   => $reviewNew,
                'review_waiting'               => $reviewWaiting,
                'withdrawals_info'             => $withdrawalsInfo,
                'email_invitation_info'        => $emailInvitationInfo,
                'tickets_new_order'            => $ticketsNewOrder,
                'miscellaneous_regarding'      => $miscellaneousRegarding,
                'platform_followers_follows'   => $platformFollowersFollows,
                'platform_followers_new_vybe'  => $platformFollowersNewVybe,
                'platform_followers_new_event' => $platformFollowersNewEvent,
                'platform_order_starts'        => $platformOrderStarts,
                'platform_invitation_info'     => $platformInvitationInfo,
                'news_receive'                 => $newsReceive
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/notificationSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param NotificationSetting $notificationSetting
     * @param User $user
     * @param bool|null $notificationEnable
     * @param bool|null $emailFollowersFollowsYou
     * @param bool|null $emailFollowersNewVybe
     * @param bool|null $emailFollowersNewEvent
     * @param bool|null $messagesUnread
     * @param bool|null $emailOrdersNew
     * @param bool|null $emailOrderStarts
     * @param bool|null $emailOrderPending
     * @param bool|null $rescheduleInfo
     * @param bool|null $reviewNew
     * @param bool|null $reviewWaiting
     * @param bool|null $withdrawalsInfo
     * @param bool|null $emailInvitationInfo
     * @param bool|null $ticketsNewOrder
     * @param bool|null $miscellaneousRegarding
     * @param bool|null $platformFollowersFollows
     * @param bool|null $platformFollowersNewVybe
     * @param bool|null $platformFollowersNewEvent
     * @param bool|null $platformOrderStarts
     * @param bool|null $platformInvitationInfo
     * @param bool|null $newsReceive
     *
     * @return NotificationSetting|null
     *
     * @throws DatabaseException
     */
    public function update(
        NotificationSetting $notificationSetting,
        User $user,
        ?bool $notificationEnable,
        ?bool $emailFollowersFollowsYou,
        ?bool $emailFollowersNewVybe,
        ?bool $emailFollowersNewEvent,
        ?bool $messagesUnread,
        ?bool $emailOrdersNew,
        ?bool $emailOrderStarts,
        ?bool $emailOrderPending,
        ?bool $rescheduleInfo,
        ?bool $reviewNew,
        ?bool $reviewWaiting,
        ?bool $withdrawalsInfo,
        ?bool $emailInvitationInfo,
        ?bool $ticketsNewOrder,
        ?bool $miscellaneousRegarding,
        ?bool $platformFollowersFollows,
        ?bool $platformFollowersNewVybe,
        ?bool $platformFollowersNewEvent,
        ?bool $platformOrderStarts,
        ?bool $platformInvitationInfo,
        ?bool $newsReceive
    ) : ?NotificationSetting
    {
        try {
            $notificationSetting->update([
                'user_id'                      => $user->id,
                'notification_enable'          => !is_null($notificationEnable) ? $notificationEnable : $notificationSetting->notification_enable,
                'email_followers_follows_you'  => !is_null($emailFollowersFollowsYou) ? $emailFollowersFollowsYou : $notificationSetting->email_followers_follows_you,
                'email_followers_new_vybe'     => !is_null($emailFollowersNewVybe) ? $emailFollowersNewVybe : $notificationSetting->email_followers_new_vybe,
                'email_followers_new_event'    => !is_null($emailFollowersNewEvent) ? $emailFollowersNewEvent : $notificationSetting->email_followers_new_event,
                'messages_unread'              => !is_null($messagesUnread) ? $messagesUnread : $notificationSetting->messages_unread,
                'email_orders_new'             => !is_null($emailOrdersNew) ? $emailOrdersNew : $notificationSetting->email_orders_new,
                'email_order_starts'           => !is_null($emailOrderStarts) ? $emailOrderStarts : $notificationSetting->email_order_starts,
                'email_order_pending'          => !is_null($emailOrderPending) ? $emailOrderPending : $notificationSetting->email_order_pending,
                'reschedule_info'              => !is_null($rescheduleInfo) ? $rescheduleInfo : $notificationSetting->reschedule_info,
                'review_new'                   => !is_null($reviewNew) ? $reviewNew : $notificationSetting->review_new,
                'review_waiting'               => !is_null($reviewWaiting) ? $reviewWaiting : $notificationSetting->review_waiting,
                'withdrawals_info'             => !is_null($withdrawalsInfo) ? $withdrawalsInfo : $notificationSetting->withdrawals_info,
                'email_invitation_info'        => !is_null($emailInvitationInfo) ? $emailInvitationInfo : $notificationSetting->email_invitation_info,
                'tickets_new_order'            => !is_null($ticketsNewOrder) ? $ticketsNewOrder : $notificationSetting->tickets_new_order,
                'miscellaneous_regarding'      => !is_null($miscellaneousRegarding) ? $miscellaneousRegarding : $notificationSetting->miscellaneous_regarding,
                'platform_followers_follows'   => !is_null($platformFollowersFollows) ? $platformFollowersFollows : $notificationSetting->platform_followers_follows,
                'platform_followers_new_vybe'  => !is_null($platformFollowersNewVybe) ? $platformFollowersNewVybe : $notificationSetting->platform_followers_new_vybe,
                'platform_followers_new_event' => !is_null($platformFollowersNewEvent) ? $platformFollowersNewEvent : $notificationSetting->platform_followers_new_event,
                'platform_order_starts'        => !is_null($platformOrderStarts) ? $platformOrderStarts : $notificationSetting->platform_order_starts,
                'platform_invitation_info'     => !is_null($platformInvitationInfo) ? $platformInvitationInfo : $notificationSetting->platform_invitation_info,
                'news_receive'                 => !is_null($newsReceive) ? $newsReceive : $notificationSetting->news_receive
            ]);

            return $notificationSetting;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/notificationSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param NotificationSetting $notificationSetting
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        NotificationSetting $notificationSetting
    ) : bool
    {
        try {
            return $notificationSetting->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/notificationSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForUser(
        User $user
    ) : bool
    {
        try {
            return $user->notificationSettings()
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/notificationSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}