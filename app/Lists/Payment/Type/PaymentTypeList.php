<?php

namespace App\Lists\Payment\Type;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PaymentTypeList
 *
 * @package App\Lists\Payment\Type
 */
class PaymentTypeList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'payment/type';

    /**
     * Payment type list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'order'
        ],
        [
            'id'   => 2,
            'code' => 'add_funds'
        ],
        [
            'id'   => 3,
            'code' => 'tip'
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
                new PaymentTypeListItem(
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
     * @return PaymentTypeListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?PaymentTypeListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new PaymentTypeListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return PaymentTypeListItem|null
     */
    public static function getOrder() : ?PaymentTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'order') {
                return new PaymentTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PaymentTypeListItem|null
     */
    public static function getAddFunds() : ?PaymentTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'add_funds') {
                return new PaymentTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PaymentTypeListItem|null
     */
    public static function getTip() : ?PaymentTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'tip') {
                return new PaymentTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}