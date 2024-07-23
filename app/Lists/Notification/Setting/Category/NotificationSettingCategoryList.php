<?php

namespace App\Lists\Notification\Setting\Category;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class NotificationSettingCategoryList
 *
 * @package App\Lists\Notification\Setting\Category
 */
class NotificationSettingCategoryList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'notification/setting/category';

    /**
     * Notification setting categories list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'notifications'
        ],
        [
            'id'   => 2,
            'code' => 'email_notifications'
        ],
        [
            'id'   => 3,
            'code' => 'platform_notifications'
        ],
        [
            'id'   => 4,
            'code' => 'newsletter_subscription'
        ]
    ];

    /**
     * List of fields requiring translation
     */
    protected const TRANSLATES = [
        'name', 'description'
    ];

    /**
     * @return Collection
     */
    public static function getItems() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            $items->push(
                new NotificationSettingCategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['description']
                )
            );
        }

        return $items;
    }

    /**
     * @param int|null $id
     *
     * @return NotificationSettingCategoryListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?NotificationSettingCategoryListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new NotificationSettingCategoryListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name'],
                $appendedItem['description']
            );
        }

        return null;
    }

    /**
     * @return NotificationSettingCategoryListItem|null
     */
    public static function getNotifications() : ?NotificationSettingCategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'notifications') {
                return new NotificationSettingCategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['description']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingCategoryListItem|null
     */
    public static function getEmailNotifications() : ?NotificationSettingCategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'email_notifications') {
                return new NotificationSettingCategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['description']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingCategoryListItem|null
     */
    public static function getPlatformNotifications() : ?NotificationSettingCategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'platform_notifications') {
                return new NotificationSettingCategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['description']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingCategoryListItem|null
     */
    public static function getNewsletterSubscription() : ?NotificationSettingCategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'newsletter_subscription') {
                return new NotificationSettingCategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['description']
                );
            }
        }

        return null;
    }
}