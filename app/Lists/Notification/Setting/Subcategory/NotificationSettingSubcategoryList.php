<?php

namespace App\Lists\Notification\Setting\Subcategory;

use App\Lists\BaseList;
use App\Lists\Notification\Setting\Category\NotificationSettingCategoryListItem;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class NotificationSettingSubcategory
 *
 * @package App\Lists\Notification\Setting\Subategory
 */
class NotificationSettingSubcategoryList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'notification/setting/subcategory';

    /**
     * Notification setting subcategories list constant
     */
    protected const ITEMS = [
        [
            'id'          => 1,
            'category_id' => 1,
            'code'        => 'notification_sounds'
        ],
        [
            'id'          => 2,
            'category_id' => 2,
            'code'        => 'notifications_followers'
        ],
        [
            'id'          => 3,
            'category_id' => 2,
            'code'        => 'messages'
        ],
        [
            'id'          => 4,
            'category_id' => 2,
            'code'        => 'orders'
        ],
        [
            'id'          => 5,
            'category_id' => 2,
            'code'        => 'reschedule'
        ],
        [
            'id'          => 6,
            'category_id' => 2,
            'code'        => 'reviews'
        ],
        [
            'id'          => 7,
            'category_id' => 2,
            'code'        => 'withdrawals'
        ],
        [
            'id'          => 8,
            'category_id' => 2,
            'code'        => 'notifications_invitation'
        ],
        [
            'id'          => 9,
            'category_id' => 2,
            'code'        => 'tickets'
        ],
        [
            'id'          => 10,
            'category_id' => 2,
            'code'        => 'miscellaneous'
        ],
        [
            'id'          => 11,
            'category_id' => 3,
            'code'        => 'platform_followers'
        ],
        [
            'id'          => 12,
            'category_id' => 3,
            'code'        => 'order'
        ],
        [
            'id'          => 13,
            'category_id' => 3,
            'code'        => 'platform_invitation'
        ],
        [
            'id'          => 14,
            'category_id' => 4,
            'code'        => 'news'
        ]
    ];

    /**
     * List of fields requiring translation
     */
    protected const TRANSLATES = [
        'name'
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
                new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                )
            );
        }

        return $items;
    }

    /**
     * @param int|null $id
     *
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?NotificationSettingSubcategoryListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new NotificationSettingSubcategoryListItem(
                $appendedItem['id'],
                $appendedItem['category_id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @param NotificationSettingCategoryListItem $notificationSettingCategoryListItem
     *
     * @return Collection
     */
    public static function getItemsByCategory(
        NotificationSettingCategoryListItem $notificationSettingCategoryListItem
    ) : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($notificationSettingCategoryListItem->id == $appendedItem['category_id']) {
                $items->push(
                    new NotificationSettingSubcategoryListItem(
                        $appendedItem['id'],
                        $appendedItem['category_id'],
                        $appendedItem['code'],
                        $appendedItem['name']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getNotificationSounds() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'notification_sounds') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getNotificationsFollowers() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'notifications_followers') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getMessages() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'messages') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getOrders() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'orders') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getReschedule() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'reschedule') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getReviews() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'reviews') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getWithdrawals() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'withdrawals') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * getNotificationsInvitation
     *
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getNotificationsInvitation() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'notifications_invitation') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getTickets() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'tickets') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getMiscellaneous() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'miscellaneous') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getPlatformFollowers() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'platform_followers') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getOrder() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'order') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getPlatformInvitation() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'platform_invitation') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingSubcategoryListItem|null
     */
    public static function getNews() : ?NotificationSettingSubcategoryListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'news') {
                return new NotificationSettingSubcategoryListItem(
                    $appendedItem['id'],
                    $appendedItem['category_id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}
