<?php

namespace App\Lists\Order\Item\Purchase\SortBy;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrderItemPurchaseSortByList
 *
 * @package App\Lists\Order\Item\Purchase\SortBy
 */
class OrderItemPurchaseSortByList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'order/item/purchase/SortBy';

    /**
     * Order item purchases sort by list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'latest_purchases_first'
        ],
        [
            'id'   => 2,
            'code' => 'earliest_purchases_first'
        ],
        [
            'id'   => 3,
            'code' => 'earliest_starting_vybes_first'
        ],
        [
            'id'   => 4,
            'code' => 'latest_starting_vybes_first'
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
                new OrderItemPurchaseSortByListItem(
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
     * @return OrderItemPurchaseSortByListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?OrderItemPurchaseSortByListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new OrderItemPurchaseSortByListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return OrderItemPurchaseSortByListItem|null
     */
    public static function getLatestPurchasesFirst() : ?OrderItemPurchaseSortByListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'latest_purchases_first') {
                return new OrderItemPurchaseSortByListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemPurchaseSortByListItem|null
     */
    public static function getEarliestPurchasesFirst() : ?OrderItemPurchaseSortByListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'earliest_purchases_first') {
                return new OrderItemPurchaseSortByListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemPurchaseSortByListItem|null
     */
    public static function getEarliestStartingVybesFirst() : ?OrderItemPurchaseSortByListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'earliest_starting_vybes_first') {
                return new OrderItemPurchaseSortByListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemPurchaseSortByListItem|null
     */
    public static function getLatestStartingVybesFirst() : ?OrderItemPurchaseSortByListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'latest_starting_vybes_first') {
                return new OrderItemPurchaseSortByListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}