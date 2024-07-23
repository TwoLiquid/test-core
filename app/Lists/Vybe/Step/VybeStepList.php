<?php

namespace App\Lists\Vybe\Step;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeStepList
 *
 * @package App\Lists\Vybe\Step
 */
class VybeStepList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'vybe/step';

    /**
     * Vybe steps list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'first'
        ],
        [
            'id'   => 2,
            'code' => 'second'
        ],
        [
            'id'   => 3,
            'code' => 'third'
        ],
        [
            'id'   => 4,
            'code' => 'fourth'
        ],
        [
            'id'   => 5,
            'code' => 'fifth'
        ],
        [
            'id'   => 6,
            'code' => 'completed'
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
                new VybeStepListItem(
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
     * @return VybeStepListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?VybeStepListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new VybeStepListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return Collection
     */
    public static function getOnlyEditSteps() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] != 'completed') {
                $items->push(
                    new VybeStepListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @return VybeStepListItem|null
     */
    public static function getFirst() : ?VybeStepListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'first') {
                return new VybeStepListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeStepListItem|null
     */
    public static function getSecond() : ?VybeStepListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'second') {
                return new VybeStepListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeStepListItem|null
     */
    public static function getThird() : ?VybeStepListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'third') {
                return new VybeStepListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeStepListItem|null
     */
    public static function getFourth() : ?VybeStepListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'fourth') {
                return new VybeStepListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeStepListItem|null
     */
    public static function getFifth() : ?VybeStepListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'fifth') {
                return new VybeStepListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeStepListItem|null
     */
    public static function getCompleted() : ?VybeStepListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'completed') {
                return new VybeStepListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}