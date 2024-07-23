<?php

namespace App\Lists\User\Theme;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserThemeList
 *
 * @package App\Lists\User\Theme
 */
class UserThemeList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'user/theme';

    /**
     * User theme list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'dark'
        ],
        [
            'id'   => 2,
            'code' => 'light'
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
                new UserThemeListItem(
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
     * @return UserThemeListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?UserThemeListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new UserThemeListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return UserThemeListItem|null
     */
    public static function getDark() : ?UserThemeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'dark') {
                return new UserThemeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return UserThemeListItem|null
     */
    public static function getLight() : ?UserThemeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'light') {
                return new UserThemeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}
