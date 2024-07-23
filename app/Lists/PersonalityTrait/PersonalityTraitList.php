<?php

namespace App\Lists\PersonalityTrait;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PersonalityTraitList
 *
 * @package App\Lists\PersonalityTrait
 */
class PersonalityTraitList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'personalityTrait';

    /**
     * Personality traits list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'friendly'
        ],
        [
            'id'   => 2,
            'code' => 'confident'
        ],
        [
            'id'   => 3,
            'code' => 'sociable'
        ],
        [
            'id'   => 4,
            'code' => 'assertive'
        ],
        [
            'id'   => 5,
            'code' => 'outgoing'
        ],
        [
            'id'   => 6,
            'code' => 'energetic'
        ],
        [
            'id'   => 7,
            'code' => 'talkative'
        ],
        [
            'id'   => 8,
            'code' => 'articulate'
        ],
        [
            'id'   => 9,
            'code' => 'affectionate'
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
                new PersonalityTraitListItem(
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
                new PersonalityTraitListItem(
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
     * @return PersonalityTraitListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?PersonalityTraitListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new PersonalityTraitListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return PersonalityTraitListItem|null
     */
    public static function getFriendly() : ?PersonalityTraitListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'friendly') {
                return new PersonalityTraitListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PersonalityTraitListItem|null
     */
    public static function getConfident() : ?PersonalityTraitListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'confident') {
                return new PersonalityTraitListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PersonalityTraitListItem|null
     */
    public static function getSociable() : ?PersonalityTraitListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'sociable') {
                return new PersonalityTraitListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PersonalityTraitListItem|null
     */
    public static function getAssertive() : ?PersonalityTraitListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'assertive') {
                return new PersonalityTraitListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PersonalityTraitListItem|null
     */
    public static function getOutgoing() : ?PersonalityTraitListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'outgoing') {
                return new PersonalityTraitListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PersonalityTraitListItem|null
     */
    public static function getEnergetic() : ?PersonalityTraitListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'energetic') {
                return new PersonalityTraitListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PersonalityTraitListItem|null
     */
    public static function getTalkative() : ?PersonalityTraitListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'talkative') {
                return new PersonalityTraitListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PersonalityTraitListItem|null
     */
    public static function getArticulate() : ?PersonalityTraitListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'articulate') {
                return new PersonalityTraitListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PersonalityTraitListItem|null
     */
    public static function getAffectionate() : ?PersonalityTraitListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'affectionate') {
                return new PersonalityTraitListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}