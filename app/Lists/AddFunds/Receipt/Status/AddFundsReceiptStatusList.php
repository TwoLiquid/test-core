<?php

namespace App\Lists\AddFunds\Receipt\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AddFundsReceiptStatusList
 * 
 * @package App\Lists\AddFunds\Receipt\Status
 */
class AddFundsReceiptStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'addFunds/receipt/status';

    /**
     * Add funds receipt status list constant
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
                new AddFundsReceiptStatusListItem(
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
     * @return AddFundsReceiptStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?AddFundsReceiptStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new AddFundsReceiptStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return AddFundsReceiptStatusListItem|null
     */
    public static function getUnpaid() : ?AddFundsReceiptStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'unpaid') {
                return new AddFundsReceiptStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AddFundsReceiptStatusListItem|null
     */
    public static function getPaid() : ?AddFundsReceiptStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'paid') {
                return new AddFundsReceiptStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AddFundsReceiptStatusListItem|null
     */
    public static function getCanceled() : ?AddFundsReceiptStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'canceled') {
                return new AddFundsReceiptStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AddFundsReceiptStatusListItem|null
     */
    public static function getRefunded() : ?AddFundsReceiptStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'refunded') {
                return new AddFundsReceiptStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}