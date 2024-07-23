<?php

namespace App\Lists\Order\Item\Sale\SortBy;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrderItemSaleSortByList
 *
 * @package App\Lists\Order\Item\Sale\SortBy
 */
class OrderItemSaleSortByList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'order/item/sale/sortBy';

    /**
     * Order item sale sort by list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'latest_sales_first'
        ],
        [
            'id'   => 2,
            'code' => 'earliest_sales_first'
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
                new OrderItemSaleSortByListItem(
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
     * @return OrderItemSaleSortByListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?OrderItemSaleSortByListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new OrderItemSaleSortByListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return OrderItemSaleSortByListItem|null
     */
    public static function getLatestSalesFirst() : ?OrderItemSaleSortByListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'latest_sales_first') {
                return new OrderItemSaleSortByListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemSaleSortByListItem|null
     */
    public static function getEarliestSalesFirst() : ?OrderItemSaleSortByListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'earliest_sales_first') {
                return new OrderItemSaleSortByListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemSaleSortByListItem|null
     */
    public static function getEarliestStartingVybesFirst() : ?OrderItemSaleSortByListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'earliest_starting_vybes_first') {
                return new OrderItemSaleSortByListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemSaleSortByListItem|null
     */
    public static function getLatestStartingVybesFirst() : ?OrderItemSaleSortByListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'latest_starting_vybes_first') {
                return new OrderItemSaleSortByListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}