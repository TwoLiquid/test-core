<?php

namespace App\Lists\Vybe\Period;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybePeriodList
 *
 * @package App\Lists\Vybe\Period
 */
class VybePeriodList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'vybe/period';

    /**
     * Vybe periods list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'ongoing'
        ],
        [
            'id'   => 2,
            'code' => 'one-time'
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
                new VybePeriodListItem(
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
     * @return VybePeriodListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?VybePeriodListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new VybePeriodListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return VybePeriodListItem|null
     */
    public static function getOngoing() : ?VybePeriodListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'ongoing') {
                return new VybePeriodListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybePeriodListItem|null
     */
    public static function getOneTime() : ?VybePeriodListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'one-time') {
                return new VybePeriodListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}