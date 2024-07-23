<?php

namespace App\Lists\Order\Item\Request\Action;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrderItemRequestActionList
 *
 * @package App\Lists\Order\Item\Request\Action
 */
class OrderItemRequestActionList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'order/item/request/action';

    /**
     * Order item request action list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'accepted'
        ],
        [
            'id'   => 2,
            'code' => 'canceled'
        ],
        [
            'id'   => 3,
            'code' => 'declined'
        ],
        [
            'id'   => 4,
            'code' => 'reschedule'
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
                new OrderItemRequestActionListItem(
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
     * @return OrderItemRequestActionListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?OrderItemRequestActionListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new OrderItemRequestActionListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return OrderItemRequestActionListItem|null
     */
    public static function getAccepted() : ?OrderItemRequestActionListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'accepted') {
                return new OrderItemRequestActionListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemRequestActionListItem|null
     */
    public static function getCanceled() : ?OrderItemRequestActionListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'canceled') {
                return new OrderItemRequestActionListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemRequestActionListItem|null
     */
    public static function getDeclined() : ?OrderItemRequestActionListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'declined') {
                return new OrderItemRequestActionListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemRequestActionListItem|null
     */
    public static function getReschedule() : ?OrderItemRequestActionListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'reschedule') {
                return new OrderItemRequestActionListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}