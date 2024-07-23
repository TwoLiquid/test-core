<?php

namespace App\Lists\Gender;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class GenderList
 *
 * @package App\Lists\Gender
 */
class GenderList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'gender';

    /**
     * Genders list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'male'
        ],
        [
            'id'   => 2,
            'code' => 'female'
        ],
        [
            'id'   => 3,
            'code' => 'other'
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
                new GenderListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                )
            );
        }

        return $items;
    }

    /**
     * @param array|null $ids
     * 
     * @return Collection
     */
    public static function getItemsByIds(
        ?array $ids
    ) : Collection
    {
        $appendedItems = static::getAppendedItemsByIds($ids);

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            $items->push(
                new GenderListItem(
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
     * @return GenderListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?GenderListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new GenderListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return GenderListItem|null
     */
    public static function getMale() : ?GenderListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'male') {
                return new GenderListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return GenderListItem|null
     */
    public static function getFemale() : ?GenderListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'female') {
                return new GenderListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return GenderListItem|null
     */
    public static function getOther() : ?GenderListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'other') {
                return new GenderListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}