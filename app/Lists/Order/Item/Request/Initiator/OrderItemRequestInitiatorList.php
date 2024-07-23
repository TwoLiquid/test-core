<?php

namespace App\Lists\Order\Item\Request\Initiator;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrderItemRequestInitiatorList
 *
 * @package App\Lists\Order\Item\Request\Initiator
 */
class OrderItemRequestInitiatorList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'order/item/request/initiator';

    /**
     * Order item request initiator list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'buyer'
        ],
        [
            'id'   => 2,
            'code' => 'seller'
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
                new OrderItemRequestInitiatorListItem(
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
     * @return OrderItemRequestInitiatorListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?OrderItemRequestInitiatorListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new OrderItemRequestInitiatorListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return OrderItemRequestInitiatorListItem|null
     */
    public static function getBuyer() : ?OrderItemRequestInitiatorListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'buyer') {
                return new OrderItemRequestInitiatorListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemRequestInitiatorListItem|null
     */
    public static function getSeller() : ?OrderItemRequestInitiatorListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'seller') {
                return new OrderItemRequestInitiatorListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}