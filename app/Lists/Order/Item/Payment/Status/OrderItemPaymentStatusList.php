<?php

namespace App\Lists\Order\Item\Payment\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrderItemPaymentStatusList
 *
 * @package App\Lists\Order\Item\Payment\Status
 */
class OrderItemPaymentStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'order/item/payment/status';

    /**
     * Order item payment statuses list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'unpaid'
        ],
        [
            'id'   => 2,
            'code' => 'paid'
        ],
        [
            'id'   => 3,
            'code' => 'canceled'
        ],
        [
            'id'   => 4,
            'code' => 'refunded'
        ],
        [
            'id'   => 5,
            'code' => 'paid_partial_refund'
        ],
        [
            'id'   => 6,
            'code' => 'chargeback'
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
                new OrderItemPaymentStatusListItem(
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
     * @return OrderItemPaymentStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?OrderItemPaymentStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new OrderItemPaymentStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return OrderItemPaymentStatusListItem|null
     */
    public static function getUnpaid() : ?OrderItemPaymentStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'unpaid') {
                return new OrderItemPaymentStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemPaymentStatusListItem|null
     */
    public static function getPaid() : ?OrderItemPaymentStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'paid') {
                return new OrderItemPaymentStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemPaymentStatusListItem|null
     */
    public static function getCanceled() : ?OrderItemPaymentStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'canceled') {
                return new OrderItemPaymentStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemPaymentStatusListItem|null
     */
    public static function getRefunded() : ?OrderItemPaymentStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'refunded') {
                return new OrderItemPaymentStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemPaymentStatusListItem|null
     */
    public static function getPaidPartialRefund() : ?OrderItemPaymentStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'paid_partial_refund') {
                return new OrderItemPaymentStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return OrderItemPaymentStatusListItem|null
     */
    public static function getChargeback() : ?OrderItemPaymentStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'chargeback') {
                return new OrderItemPaymentStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}