<?php

namespace App\Lists\User\Balance\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserBalanceStatusList
 *
 * @package App\Lists\User\Balance\Status
 */
class UserBalanceStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'user/balance/status';

    /**
     * User balance statuses list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'inactive'
        ],
        [
            'id'   => 2,
            'code' => 'active'
        ],
        [
            'id'   => 3,
            'code' => 'deactivated'
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
                new UserBalanceStatusListItem(
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
     * @return UserBalanceStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?UserBalanceStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new UserBalanceStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return UserBalanceStatusListItem|null
     */
    public static function getInactive() : ?UserBalanceStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'inactive') {
                return new UserBalanceStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return UserBalanceStatusListItem|null
     */
    public static function getActive() : ?UserBalanceStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'active') {
                return new UserBalanceStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return UserBalanceStatusListItem|null
     */
    public static function getDeactivated() : ?UserBalanceStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'deactivated') {
                return new UserBalanceStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}