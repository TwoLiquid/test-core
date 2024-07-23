<?php

namespace App\Repositories\Notification\Interfaces;

use App\Models\MySql\NotificationSetting;
use App\Models\MySql\User\User;

/**
 * Interface NotificationSettingRepositoryInterface
 *
 * @package App\Repositories\Notification\Interfaces
 */
interface NotificationSettingRepositoryInterface
{
    /**
     * This method provides checking row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return bool
     */
    public function existsForUser(
        User $user
    ) : bool;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
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
    ) : ?NotificationSetting;

    /**
     * This method provides updating new row
     * with an eloquent model
     *
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
    ) : ?NotificationSetting;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param NotificationSetting $notificationSetting
     *
     * @return bool
     */
    public function delete(
        NotificationSetting $notificationSetting
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param User $user
     *
     * @return bool
     */
    public function deleteForUser(
        User $user
    ) : bool;
}
