<?php

namespace App\Lists\User\State\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserStateStatusList
 *
 * @package App\Lists\User\State\Status
 */
class UserStateStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'user/state/status';

    /**
     * User state statuses list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'online'
        ],
        [
            'id'   => 2,
            'code' => 'idle'
        ],
        [
            'id'   => 3,
            'code' => 'offline'
        ],
        [
            'id'   => 4,
            'code' => 'invisible'
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
                new UserStateStatusListItem(
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
     * @return UserStateStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?UserStateStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new UserStateStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return UserStateStatusListItem|null
     */
    public static function getOnline() : ?UserStateStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'online') {
                return new UserStateStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return UserStateStatusListItem|null
     */
    public static function getIdle() : ?UserStateStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'idle') {
                return new UserStateStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return UserStateStatusListItem|null
     */
    public static function getOffline() : ?UserStateStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'offline') {
                return new UserStateStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return UserStateStatusListItem|null
     */
    public static function getInvisible() : ?UserStateStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'invisible') {
                return new UserStateStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}