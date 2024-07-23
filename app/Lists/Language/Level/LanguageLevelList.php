<?php

namespace App\Lists\Language\Level;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class LanguageLevelList
 *
 * @package App\Lists\Language\Level
 */
class LanguageLevelList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'language/level';

    /**
     * Language levels list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'basic'
        ],
        [
            'id'   => 2,
            'code' => 'proficient'
        ],
        [
            'id'   => 3,
            'code' => 'fluent'
        ],
        [
            'id'   => 4,
            'code' => 'native'
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
                new LanguageLevelListItem(
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
     * @return LanguageLevelListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?LanguageLevelListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new LanguageLevelListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return LanguageLevelListItem|null
     */
    public static function getBasic() : ?LanguageLevelListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'basic') {
                return new LanguageLevelListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageLevelListItem|null
     */
    public static function getProficient() : ?LanguageLevelListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'proficient') {
                return new LanguageLevelListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageLevelListItem|null
     */
    public static function getFluent() : ?LanguageLevelListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'fluent') {
                return new LanguageLevelListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return LanguageLevelListItem|null
     */
    public static function getNative() : ?LanguageLevelListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'native') {
                return new LanguageLevelListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}