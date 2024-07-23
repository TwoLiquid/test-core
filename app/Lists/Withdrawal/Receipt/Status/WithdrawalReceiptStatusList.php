<?php

namespace App\Lists\Withdrawal\Receipt\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class WithdrawalReceiptStatusList
 *
 * @package App\Lists\Withdrawal\Receipt\Status
 */
class WithdrawalReceiptStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'withdrawal/receipt/status';

    /**
     * Seller withdrawal receipt status list constant
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
            'code' => 'credit'
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
                new WithdrawalReceiptStatusListItem(
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
     * @return WithdrawalReceiptStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?WithdrawalReceiptStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new WithdrawalReceiptStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return WithdrawalReceiptStatusListItem|null
     */
    public static function getUnpaid() : ?WithdrawalReceiptStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'unpaid') {
                return new WithdrawalReceiptStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return WithdrawalReceiptStatusListItem|null
     */
    public static function getPaid() : ?WithdrawalReceiptStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'paid') {
                return new WithdrawalReceiptStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return WithdrawalReceiptStatusListItem|null
     */
    public static function getCredit() : ?WithdrawalReceiptStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'credit') {
                return new WithdrawalReceiptStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}