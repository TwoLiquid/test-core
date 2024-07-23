<?php

namespace App\Lists\Alert\Text\Style;

use App\Lists\Alert\Type\AlertTypeListItem;
use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AlertTextStyleList
 *
 * @package App\Lists\Alert\Text\Style
 */
class AlertTextStyleList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'alert/text/style';

    /**
     * Alert text styles list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'regular'
        ],
        [
            'id'   => 2,
            'code' => 'medium'
        ],
        [
            'id'   => 3,
            'code' => 'semibold'
        ],
        [
            'id'   => 4,
            'code' => 'bold'
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
                new AlertTextStyleListItem(
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
     * @return AlertTextStyleListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?AlertTextStyleListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new AlertTextStyleListItem(
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
     * @return AlertTextStyleListItem|null
     */
    public static function getDefault(
        AlertTypeListItem $alertTypeListItem
    ) : ?AlertTextStyleListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == config('alert.' . $alertTypeListItem->code . '.text_align')) {
                return new AlertTextStyleListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextStyleListItem|null
     */
    public static function getRegular() : ?AlertTextStyleListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'regular') {
                return new AlertTextStyleListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextStyleListItem|null
     */
    public static function getMedium() : ?AlertTextStyleListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'medium') {
                return new AlertTextStyleListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextStyleListItem|null
     */
    public static function getSemibold() : ?AlertTextStyleListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'semibold') {
                return new AlertTextStyleListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextStyleListItem|null
     */
    public static function getBold() : ?AlertTextStyleListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'bold') {
                return new AlertTextStyleListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}