<?php

namespace App\Lists\Order\Item\Request\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrderItemRequestStatusList
 *
 * @package App\Lists\Order\Item\Request\Status
 */
class OrderItemRequestStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'order/item/request/status';

    /**
     * Order item request status list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'active'
        ],
        [
            'id'   => 2,
            'code' => 'finished'
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
                new OrderItemRequestStatusListItem(
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
     * @return OrderItemRequestStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?OrderItemRequestStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new OrderItemRequestStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return OrderItemRequestStatusListItem|null
     */
    public static function getActive() : ?OrderItemRequestStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'active') {
                return new OrderItemRequestStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemRequestStatusListItem|null
     */
    public static function getFinished() : ?OrderItemRequestStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'finished') {
                return new OrderItemRequestStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}