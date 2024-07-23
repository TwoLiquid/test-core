<?php

namespace App\Lists\Account\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AccountStatusList
 * 
 * @package App\Lists\Account\Status
 */
class AccountStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'account/status';

    /**
     * Account statuses list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'pending'
        ],
        [
            'id'   => 2,
            'code' => 'active'
        ],
        [
            'id'   => 3,
            'code' => 'suspended'
        ],
        [
            'id'   => 4,
            'code' => 'deactivated'
        ],
        [
            'id'   => 5,
            'code' => 'deleted'
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
                new AccountStatusListItem(
                    $appendedItem['id'],
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
     * @return AccountStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?AccountStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new AccountStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return AccountStatusListItem|null
     */
    public static function getPending() : ?AccountStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'pending') {
                return new AccountStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AccountStatusListItem|null
     */
    public static function getActive() : ?AccountStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'active') {
                return new AccountStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AccountStatusListItem|null
     */
    public static function getSuspended() : ?AccountStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'suspended') {
                return new AccountStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AccountStatusListItem|null
     */
    public static function getDeactivated() : ?AccountStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'deactivated') {
                return new AccountStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AccountStatusListItem|null
     */
    public static function getDeleted() : ?AccountStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'deleted') {
                return new AccountStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}