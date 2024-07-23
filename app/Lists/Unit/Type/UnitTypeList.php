<?php

namespace App\Lists\Unit\Type;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UnitTypeList
 *
 * @package App\Lists\Unit\Type
 */
class UnitTypeList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'unit/type';

    /**
     * Unit type list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'usual'
        ],
        [
            'id'   => 2,
            'code' => 'event'
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
                new UnitTypeListItem(
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
     * @return UnitTypeListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?UnitTypeListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new UnitTypeListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return UnitTypeListItem|null
     */
    public static function getUsual() : ?UnitTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'usual') {
                return new UnitTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return UnitTypeListItem|null
     */
    public static function getEvent() : ?UnitTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'event') {
                return new UnitTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}