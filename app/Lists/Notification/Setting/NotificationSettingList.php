<?php

namespace App\Lists\Notification\Setting;

use App\Lists\BaseList;
use App\Lists\Notification\Setting\Subcategory\NotificationSettingSubcategoryListItem;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class NotificationSettingList
 *
 * @package App\Lists\Notification\Setting
 */
class NotificationSettingList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'notification/setting/setting';

    /**
     * Notification settings list constant
     */
    protected const ITEMS = [
        [
            'id'             => 1,
            'subcategory_id' => 1,
            'code'           => 'notification_enable',
            'default_value'  => true
        ],
        [
            'id'             => 2,
            'subcategory_id' => 2,
            'code'           => 'email_followers_follows_you',
            'default_value'  => false
        ],
        [
            'id'             => 3,
            'subcategory_id' => 2,
            'code'           => 'email_followers_new_vybe',
            'default_value'  => false
        ],
        [
            'id'             => 4,
            'subcategory_id' => 2,
            'code'           => 'email_followers_new_event',
            'default_value'  => false
        ],
        [
            'id'             => 5,
            'subcategory_id' => 3,
            'code'           => 'messages_unread',
            'default_value'  => false
        ],
        [
            'id'             => 6,
            'subcategory_id' => 4,
            'code'           => 'email_orders_new',
            'default_value'  => true
        ],
        [
            'id'             => 7,
            'subcategory_id' => 4,
            'code'           => 'email_order_starts',
            'default_value'  => false
        ],
        [
            'id'             => 8,
            'subcategory_id' => 4,
            'code'           => 'email_order_pending',
            'default_value'  => false
        ],
        [
            'id'             => 9,
            'subcategory_id' => 5,
            'code'           => 'reschedule_info',
            'default_value'  => true
        ],
        [
            'id'             => 10,
            'subcategory_id' => 6,
            'code'           => 'review_new',
            'default_value'  => false
        ],
        [
            'id'             => 11,
            'subcategory_id' => 6,
            'code'           => 'review_waiting',
            'default_value'  => false
        ],
        [
            'id'             => 12,
            'subcategory_id' => 7,
            'code'           => 'withdrawals_info',
            'default_value'  => false
        ],
        [
            'id'             => 13,
            'subcategory_id' => 8,
            'code'           => 'email_invitation_info',
            'default_value'  => false
        ],
        [
            'id'             => 14,
            'subcategory_id' => 9,
            'code'           => 'tickets_new_order',
            'default_value'  => true
        ],
        [
            'id'             => 15,
            'subcategory_id' => 10,
            'code'           => 'miscellaneous_regarding',
            'default_value'  => false
        ],
        [
            'id'             => 16,
            'subcategory_id' => 11,
            'code'           => 'platform_followers_follows',
            'default_value'  => true
        ],
        [
            'id'             => 17,
            'subcategory_id' => 11,
            'code'           => 'platform_followers_new_vybe',
            'default_value'  => false
        ],
        [
            'id'             => 18,
            'subcategory_id' => 11,
            'code'           => 'platform_followers_new_event',
            'default_value'  => false
        ],
        [
            'id'             => 19,
            'subcategory_id' => 12,
            'code'           => 'platform_order_starts',
            'default_value'  => true
        ],
        [
            'id'             => 20,
            'subcategory_id' => 13,
            'code'           => 'platform_invitation_info',
            'default_value'  => true
        ],
        [
            'id'             => 21,
            'subcategory_id' => 14,
            'code'           => 'news_receive',
            'default_value'  => true
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
                new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                )
            );
        }

        return $items;
    }

    /**
     * @return array
     */
    public static function getItemsValuesAsArray() : array
    {
        $appendedItems = static::getAppendedItems();

        $items = [];

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            $items[$appendedItem['code']] = $appendedItem['default_value'];
        }

        return $items;
    }

    /**
     * @param int|null $id
     *
     * @return NotificationSettingListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?NotificationSettingListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new NotificationSettingListItem(
                $appendedItem['id'],
                $appendedItem['subcategory_id'],
                $appendedItem['code'],
                $appendedItem['name'],
                $appendedItem['default_value']
            );
        }

        return null;
    }

    /**
     * @param NotificationSettingSubcategoryListItem $notificationSettingSubcategoryListItem
     *
     * @return Collection
     */
    public static function getItemsBySubcategory(
        NotificationSettingSubcategoryListItem $notificationSettingSubcategoryListItem
    ) : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($notificationSettingSubcategoryListItem->id == $appendedItem['subcategory_id']) {
                $items->push(
                    new NotificationSettingListItem(
                        $appendedItem['id'],
                        $appendedItem['subcategory_id'],
                        $appendedItem['code'],
                        $appendedItem['name'],
                        $appendedItem['default_value']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getNotificationEnable() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'notification_enable') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getEmailFollowersFollowsYou() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'email_followers_follows_you') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getEmailFollowersNewVybe() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'email_followers_new_vybe') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getEmailFollowersNewEvent() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'email_followers_new_event') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getMessagesUnread() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'messages_unread') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getEmailOrdersNew() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'email_orders_new') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getEmailOrderStarts() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'email_order_starts') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getEmailOrderPending() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'email_order_pending') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getRescheduleInfo() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'reschedule_info') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getReviewNew() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'review_new') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getReviewWaiting() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'review_waiting') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getWithdrawalsInfo() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'withdrawals_info') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getEmailInvitationInfo() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'email_invitation_info') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getTicketsNewOrder() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'tickets_new_order') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getMiscellaneousRegarding() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'miscellaneous_regarding') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getPlatformFollowersFollows() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'platform_followers_follows') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getPlatformFollowersNewVybe() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'platform_followers_new_vybe') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getPlatformFollowersNewEvent() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'platform_followers_new_event') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getPlatformOrderStarts() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'platform_order_starts') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getPlatformInvitationInfo() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'platform_invitation_info') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }

    /**
     * @return NotificationSettingListItem|null
     */
    public static function getNewsReceive() : ?NotificationSettingListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'news_receive') {
                return new NotificationSettingListItem(
                    $appendedItem['id'],
                    $appendedItem['subcategory_id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['default_value']
                );
            }
        }

        return null;
    }
}