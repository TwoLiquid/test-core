<?php

namespace App\Lists\Notification\Setting;

/**
 * Class NotificationSettingListItem
 *
 * @property int $id
 * @property int $subcategoryId
 * @property string $code
 * @property string $name
 * @property string $defaultValue
 *
 * @package App\Lists\Notification\Setting
 */
class NotificationSettingListItem
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $subcategoryId;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $defaultValue;

    /**
     * NotificationSettingListItem constructor
     *
     * @param int $id
     * @param int $subcategoryId
     * @param string $code
     * @param string $name
     * @param bool $defaultValue
     */
    public function __construct(
        int $id,
        int $subcategoryId,
        string $code,
        string $name,
        bool $defaultValue
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var int subcategoryId */
        $this->subcategoryId = $subcategoryId;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;

        /** @var bool defaultValue */
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return bool
     */
    public function isNotificationEnable() : bool
    {
        return $this->code == 'notification_enable';
    }

    /**
     * @return bool
     */
    public function isEmailFollowersFollowsYou() : bool
    {
        return $this->code == 'email_followers_follows_you';
    }

    /**
     * @return bool
     */
    public function isEmailFollowersNewVybe() : bool
    {
        return $this->code == 'email_followers_new_vybe';
    }

    /**
     * @return bool
     */
    public function isEmailFollowersNewEvent() : bool
    {
        return $this->code == 'email_followers_new_event';
    }

    /**
     * @return bool
     */
    public function isMessagesUnread() : bool
    {
        return $this->code == 'messages_unread';
    }

    /**
     * @return bool
     */
    public function isEmailOrdersNew() : bool
    {
        return $this->code == 'email_orders_new';
    }

    /**
     * @return bool
     */
    public function isEmailOrderStarts() : bool
    {
        return $this->code == 'email_order_starts';
    }

    /**
     * @return bool
     */
    public function isEmailOrderPending() : bool
    {
        return $this->code == 'email_order_pending';
    }

    /**
     * @return bool
     */
    public function isRescheduleInfo() : bool
    {
        return $this->code == 'reschedule_info';
    }

    /**
     * @return bool
     */
    public function isReviewNew() : bool
    {
        return $this->code == 'review_new';
    }

    /**
     * @return bool
     */
    public function isReviewWaiting() : bool
    {
        return $this->code == 'review_waiting';
    }

    /**
     * @return bool
     */
    public function isWithdrawalsInfo() : bool
    {
        return $this->code == 'withdrawals_info';
    }

    /**
     * @return bool
     */
    public function isEmailInvitationInfo() : bool
    {
        return $this->code == 'email_invitation_info';
    }

    /**
     * @return bool
     */
    public function isTicketsNewOrder() : bool
    {
        return $this->code == 'tickets_new_order';
    }

    /**
     * @return bool
     */
    public function isMiscellaneousRegarding() : bool
    {
        return $this->code == 'miscellaneous_regarding';
    }

    /**
     * @return bool
     */
    public function isPlatformFollowersFollows() : bool
    {
        return $this->code == 'platform_followers_follows';
    }

    /**
     * @return bool
     */
    public function isPlatformFollowersNewVybe() : bool
    {
        return $this->code == 'platform_followers_new_vybe';
    }

    /**
     * @return bool
     */
    public function isPlatformFollowersNewEvent() : bool
    {
        return $this->code == 'platform_followers_new_event';
    }

    /**
     * @return bool
     */
    public function isPlatformOrderStarts() : bool
    {
        return $this->code == 'platform_order_starts';
    }

    /**
     * @return bool
     */
    public function isPlatformInvitationInfo() : bool
    {
        return $this->code == 'platform_invitation_info';
    }

    /**
     * @return bool
     */
    public function isNewsReceive() : bool
    {
        return $this->code == 'news_receive';
    }
}