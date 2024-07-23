<?php

namespace App\Lists\Notification\Setting\Category;

/**
 * Class NotificationSettingCategoryListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $description
 *
 * @package App\Lists\Notification\Setting\Category
 */
class NotificationSettingCategoryListItem
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string|null
     */
    public ?string $description;

    /**
     * NotificationSettingCategoryListItem constructor
     *
     * @param int $id
     * @param string $code
     * @param string $name
     * @param string|null $description
     */
    public function __construct(
        int $id,
        string $code,
        string $name,
        ?string $description
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;

        /** @var string description */
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isNotifications() : bool
    {
        return $this->code == 'notifications';
    }

    /**
     * @return bool
     */
    public function isEmailNotifications() : bool
    {
        return $this->code == 'email_notifications';
    }

    /**
     * @return bool
     */
    public function isPlatformNotifications() : bool
    {
        return $this->code == 'platform_notifications';
    }

    /**
     * @return bool
     */
    public function isNewsletterSubscription() : bool
    {
        return $this->code == 'newsletter_subscription';
    }
}