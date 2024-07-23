<?php

namespace App\Lists\Vybe\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeStatusList
 *
 * @package App\Lists\Vybe\Status
 */
class VybeStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'vybe/status';

    /**
     * Vybe statuses list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'draft'
        ],
        [
            'id'   => 2,
            'code' => 'published'
        ],
        [
            'id'   => 3,
            'code' => 'paused'
        ],
        [
            'id'   => 4,
            'code' => 'suspended'
        ],
        [
            'id'   => 5,
            'code' => 'deleted'
        ]
    ];

    /**
     * Accessible statuses for client
     */
    protected const CLIENT_STATUSES  = [
        'draft' => [
            'draft',
            'published',
            'deleted'
        ],
        'published' => [
            'draft',
            'published',
            'paused',
            'deleted'
        ],
        'paused' => [
            'draft',
            'published',
            'paused',
            'deleted'
        ],
        'suspended' => [
            'draft',
            'suspended',
            'deleted'
        ],
        'deleted' => []
    ];

    /**
     * Accessible statuses for admin
     */
    protected const ADMIN_STATUSES  = [
        'draft' => [
            'draft',
            'published',
            'paused',
            'suspended',
            'deleted'
        ],
        'published' => [
            'draft',
            'published',
            'paused',
            'suspended',
            'deleted'
        ],
        'paused' => [
            'draft',
            'published',
            'paused',
            'suspended',
            'deleted'
        ],
        'suspended' => [
            'draft',
            'published',
            'paused',
            'suspended',
            'deleted'
        ],
        'deleted' => [
            'draft',
            'published',
            'paused',
            'suspended',
            'deleted'
        ]
    ];

    /**
     * Profile available statuses
     */
    protected const PROFILE_AVAILABLE_STATUSES  = [
        'published',
        'paused'
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
                new VybeStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                )
            );
        }

        return $items;
    }

    /**
     * @param array $statusesIds
     *
     * @return Collection
     */
    public static function getItemsByIds(
        array $statusesIds
    ) : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if (in_array($appendedItem['id'], $statusesIds)) {
                $items->push(
                    new VybeStatusListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @param VybeStatusListItem $vybeStatusListItem
     *
     * @return Collection
     */
    public static function getClientItemsForStatus(
        VybeStatusListItem $vybeStatusListItem
    ) : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if (in_array($appendedItem['code'], self::CLIENT_STATUSES[$vybeStatusListItem->code])) {
                $items->push(
                    new VybeStatusListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @param VybeStatusListItem $vybeStatusListItem
     *
     * @return Collection
     */
    public static function getAdminItemsForStatus(
        VybeStatusListItem $vybeStatusListItem
    ) : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if (in_array($appendedItem['code'], self::ADMIN_STATUSES[$vybeStatusListItem->code])) {
                $items->push(
                    new VybeStatusListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @return Collection
     */
    public static function getAvailableForProfile() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if (in_array($appendedItem['code'], self::PROFILE_AVAILABLE_STATUSES)) {
                $items->push(
                    new VybeStatusListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @param int|null $id
     *
     * @return VybeStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?VybeStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new VybeStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @param string $code
     *
     * @return VybeStatusListItem|null
     */
    public static function getItemByCode(
        string $code
    ) : ?VybeStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == $code) {
                return new VybeStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeStatusListItem|null
     */
    public static function getDraftItem() : ?VybeStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'draft') {
                return new VybeStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeStatusListItem|null
     */
    public static function getPublishedItem() : ?VybeStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'published') {
                return new VybeStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeStatusListItem|null
     */
    public static function getPausedItem() : ?VybeStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'paused') {
                return new VybeStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeStatusListItem|null
     */
    public static function getSuspendedItem() : ?VybeStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'suspended') {
                return new VybeStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeStatusListItem|null
     */
    public static function getDeletedItem() : ?VybeStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'deleted') {
                return new VybeStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}
