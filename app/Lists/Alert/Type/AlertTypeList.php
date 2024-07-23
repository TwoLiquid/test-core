<?php

namespace App\Lists\Alert\Type;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AlertTypeList
 *
 * @package App\Lists\Alert\Type
 */
class AlertTypeList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'alert/type';

    /**
     * Alert type list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'order'
        ],
        [
            'id'   => 2,
            'code' => 'tipping'
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
                new AlertTypeListItem(
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
     * @return AlertTypeListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?AlertTypeListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new AlertTypeListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return AlertTypeListItem|null
     */
    public static function getOrder() : ?AlertTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'order') {
                return new AlertTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTypeListItem|null
     */
    public static function getTipping() : ?AlertTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'tipping') {
                return new AlertTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}