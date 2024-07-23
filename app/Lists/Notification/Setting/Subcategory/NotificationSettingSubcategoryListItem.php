<?php

namespace App\Lists\Notification\Setting\Subcategory;

/**
 * Class NotificationSettingCategoryListItem
 *
 * @property int $id
 * @property int $categoryId
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Notification\Setting\Subcategory
 */
class NotificationSettingSubcategoryListItem
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $categoryId;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $name;

    /**
     * NotificationSettingSubcategoryListItem constructor
     *
     * @param int $id
     * @param int $categoryId
     * @param string $code
     * @param string $name
     */
    public function __construct(
        int $id,
        int $categoryId,
        string $code,
        string $name
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var int categoryId */
        $this->categoryId = $categoryId;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isNotificationSounds() : bool
    {
        return $this->code == 'notification_sounds';
    }

    /**
     * @return bool
     */
    public function isNotificationsFollowers() : bool
    {
        return $this->code == 'notifications_followers';
    }

    /**
     * @return bool
     */
    public function isMessages() : bool
    {
        return $this->code == 'messages';
    }

    /**
     * @return bool
     */
    public function isOrders() : bool
    {
        return $this->code == 'orders';
    }

    /**
     * @return bool
     */
    public function isReschedule() : bool
    {
        return $this->code == 'reschedule';
    }

    /**
     * @return bool
     */
    public function isReviews() : bool
    {
        return $this->code == 'reviews';
    }

    /**
     * @return bool
     */
    public function isWithdrawals() : bool
    {
        return $this->code == 'withdrawals';
    }

    /**
     * @return bool
     */
    public function isNotificationsInvitation() : bool
    {
        return $this->code == 'notifications_invitation';
    }

    /**
     * @return bool
     */
    public function isTickets() : bool
    {
        return $this->code == 'tickets';
    }

    /**
     * @return bool
     */
    public function isMiscellaneous() : bool
    {
        return $this->code == 'miscellaneous';
    }

    /**
     * @return bool
     */
    public function isPlatformFollowers() : bool
    {
        return $this->code == 'platform_followers';
    }

    /**
     * @return bool
     */
    public function isOrder() : bool
    {
        return $this->code == 'order';
    }

    /**
     * @return bool
     */
    public function isPlatformInvitation() : bool
    {
        return $this->code == 'platform_invitation';
    }

    /**
     * @return bool
     */
    public function isNews() : bool
    {
        return $this->code == 'news';
    }
}