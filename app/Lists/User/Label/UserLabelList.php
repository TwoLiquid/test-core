<?php

namespace App\Lists\User\Label;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserLabelList
 *
 * @package App\Lists\User\Label
 */
class UserLabelList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'user/label';

    /**
     * User labels list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'streamer'
        ],
        [
            'id'   => 2,
            'code' => 'deactivate'
        ],
        [
            'id'   => 3,
            'code' => 'blocked'
        ],
        [
            'id'   => 4,
            'code' => 'creator'
        ],
        [
            'id'   => 5,
            'code' => 'suspended'
        ]
    ];

    /**
     * List of fields requiring translation
     */
    protected const TRANSLATES = [
        'name', 'comment'
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
                new UserLabelListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['comment']
                )
            );
        }

        return $items;
    }

    /**
     * @param int|null $id
     *
     * @return UserLabelListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?UserLabelListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new UserLabelListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name'],
                $appendedItem['comment']
            );
        }

        return null;
    }

    /**
     * @return UserLabelListItem|null
     */
    public static function getStreamer() : ?UserLabelListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'streamer') {
                return new UserLabelListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['comment']
                );
            }
        }

        return null;
    }

    /**
     * @return UserLabelListItem|null
     */
    public static function getDeactivate() : ?UserLabelListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'deactivate') {
                return new UserLabelListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['comment']
                );
            }
        }

        return null;
    }

    /**
     * @return UserLabelListItem|null
     */
    public static function getBlocked() : ?UserLabelListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'blocked') {
                return new UserLabelListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['comment']
                );
            }
        }

        return null;
    }

    /**
     * @return UserLabelListItem|null
     */
    public static function getCreator() : ?UserLabelListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'creator') {
                return new UserLabelListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['comment']
                );
            }
        }

        return null;
    }

    /**
     * @return UserLabelListItem|null
     */
    public static function getSuspended() : ?UserLabelListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'suspended') {
                return new UserLabelListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['comment']
                );
            }
        }

        return null;
    }
}