<?php

namespace App\Lists\Alert\Cover;

use App\Lists\Alert\Type\AlertTypeListItem;
use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AlertCoverList
 *
 * @package App\Lists\Alert\Cover
 */
class AlertCoverList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'alert/cover';

    /**
     * Alert cover list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'none'
        ],
        [
            'id'   => 2,
            'code' => 'gradient_string_cover'
        ],
        [
            'id'   => 3,
            'code' => 'gradient_box_cover'
        ],
        [
            'id'   => 4,
            'code' => 'solid_box_cover'
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
                new AlertCoverListItem(
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
     * @return AlertCoverListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?AlertCoverListItem
    {
        $appendedItem = static::getAppendedItem($id);

            if ($appendedItem) {
            return new AlertCoverListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @param AlertTypeListItem $alertTypeListItem
     *
     * @return AlertCoverListItem|null
     */
    public static function getDefault(
        AlertTypeListItem $alertTypeListItem
    ) : ?AlertCoverListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == config('alert.' . $alertTypeListItem->code . '.cover')) {
                return new AlertCoverListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertCoverListItem|null
     */
    public static function getNone() : ?AlertCoverListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'none') {
                return new AlertCoverListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertCoverListItem|null
     */
    public static function getGradientStringCover() : ?AlertCoverListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'gradient_string_cover') {
                return new AlertCoverListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertCoverListItem|null
     */
    public static function getGradientBoxCover() : ?AlertCoverListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'gradient_box_cover') {
                return new AlertCoverListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertCoverListItem|null
     */
    public static function getSolidBoxCover() : ?AlertCoverListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'solid_box_cover') {
                return new AlertCoverListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}