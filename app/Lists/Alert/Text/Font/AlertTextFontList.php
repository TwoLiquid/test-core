<?php

namespace App\Lists\Alert\Text\Font;

use App\Lists\Alert\Type\AlertTypeListItem;
use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AlertTextFontList
 *
 * @package App\Lists\Alert\Text\Font
 */
class AlertTextFontList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'alert/text/font';

    /**
     * Alert text fonts list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'montserrat'
        ],
        [
            'id'   => 2,
            'code' => 'inter'
        ],
        [
            'id'   => 3,
            'code' => 'oswald'
        ],
        [
            'id'   => 4,
            'code' => 'raleway'
        ],
        [
            'id'   => 5,
            'code' => 'playfair_display'
        ],
        [
            'id'   => 6,
            'code' => 'comfortaa'
        ],
        [
            'id'   => 7,
            'code' => 'caveat'
        ],
        [
            'id'   => 8,
            'code' => 'jura'
        ],
        [
            'id'   => 9,
            'code' => 'vollkorn'
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
                new AlertTextFontListItem(
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
     * @return AlertTextFontListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?AlertTextFontListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new AlertTextFontListItem(
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
     * @return AlertTextFontListItem|null
     */
    public static function getDefault(
        AlertTypeListItem $alertTypeListItem
    ) : ?AlertTextFontListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == config('alert.' . $alertTypeListItem->code . '.text_font')) {
                return new AlertTextFontListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextFontListItem|null
     */
    public static function getMontserrat() : ?AlertTextFontListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'montserrat') {
                return new AlertTextFontListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextFontListItem|null
     */
    public static function getInter() : ?AlertTextFontListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'inter') {
                return new AlertTextFontListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextFontListItem|null
     */
    public static function getOswald() : ?AlertTextFontListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'oswald') {
                return new AlertTextFontListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextFontListItem|null
     */
    public static function getRaleway() : ?AlertTextFontListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'raleway') {
                return new AlertTextFontListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextFontListItem|null
     */
    public static function getPlayfairDisplay() : ?AlertTextFontListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'playfair_display') {
                return new AlertTextFontListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextFontListItem|null
     */
    public static function getComfortaa() : ?AlertTextFontListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'comfortaa') {
                return new AlertTextFontListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextFontListItem|null
     */
    public static function getCaveat() : ?AlertTextFontListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'caveat') {
                return new AlertTextFontListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextFontListItem|null
     */
    public static function getJura() : ?AlertTextFontListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'jura') {
                return new AlertTextFontListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertTextFontListItem|null
     */
    public static function getVollkorn() : ?AlertTextFontListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'vollkorn') {
                return new AlertTextFontListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}