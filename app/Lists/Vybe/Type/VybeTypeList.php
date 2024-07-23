<?php

namespace App\Lists\Vybe\Type;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeTypeList
 *
 * @package App\Lists\Vybe\Type
 */
class VybeTypeList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'vybe/type';

    /**
     * Vybe types list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'solo'
        ],
        [
            'id'   => 2,
            'code' => 'group'
        ],
        [
            'id'   => 3,
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
                new VybeTypeListItem(
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
     * @return VybeTypeListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?VybeTypeListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new VybeTypeListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return VybeTypeListItem|null
     */
    public static function getSoloItem() : ?VybeTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'solo') {
                return new VybeTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeTypeListItem|null
     */
    public static function getGroupItem() : ?VybeTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'group') {
                return new VybeTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeTypeListItem|null
     */
    public static function getEventItem() : ?VybeTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'event') {
                return new VybeTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}