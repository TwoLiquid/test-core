<?php

namespace App\Lists\Payment\Method\Payment\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PaymentStatusList
 *
 * @package App\Lists\Payment\Method\Payment\Status
 */
class PaymentStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'payment/method/payment/status';

    /**
     * Payment method payment status list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'active'
        ],
        [
            'id'   => 2,
            'code' => 'inactive'
        ],
        [
            'id'   => 3,
            'code' => 'paused'
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
                new PaymentStatusListItem(
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
     * @return PaymentStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?PaymentStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new PaymentStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return PaymentStatusListItem|null
     */
    public static function getActive() : ?PaymentStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'active') {
                return new PaymentStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PaymentStatusListItem|null
     */
    public static function getInactive() : ?PaymentStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'inactive') {
                return new PaymentStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PaymentStatusListItem|null
     */
    public static function getPaused() : ?PaymentStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'paused') {
                return new PaymentStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}