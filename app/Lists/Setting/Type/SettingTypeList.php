<?php

namespace App\Lists\Setting\Type;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SettingTypeList
 *
 * @package App\Lists\Setting\Type
 */
class SettingTypeList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'setting/type';

    /**
     * Setting type list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'default'
        ],
        [
            'id'   => 2,
            'code' => 'custom'
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
                new SettingTypeListItem(
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
     * @return SettingTypeListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?SettingTypeListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new SettingTypeListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return SettingTypeListItem|null
     */
    public static function getDefault() : ?SettingTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'default') {
                return new SettingTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return SettingTypeListItem|null
     */
    public static function getCustom() : ?SettingTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'custom') {
                return new SettingTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}