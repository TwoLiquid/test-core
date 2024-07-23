<?php

namespace App\Lists\Payment\Method\Withdrawal\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PaymentMethodWithdrawalStatusList
 *
 * @package App\Lists\Payment\Method\Withdrawal\Status
 */
class PaymentMethodWithdrawalStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'payment/method/withdrawal/status';

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
                new PaymentMethodWithdrawalStatusListItem(
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
     * @return PaymentMethodWithdrawalStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?PaymentMethodWithdrawalStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new PaymentMethodWithdrawalStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return PaymentMethodWithdrawalStatusListItem|null
     */
    public static function getActive() : ?PaymentMethodWithdrawalStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'active') {
                return new PaymentMethodWithdrawalStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PaymentMethodWithdrawalStatusListItem|null
     */
    public static function getInactive() : ?PaymentMethodWithdrawalStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'inactive') {
                return new PaymentMethodWithdrawalStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PaymentMethodWithdrawalStatusListItem|null
     */
    public static function getPaused() : ?PaymentMethodWithdrawalStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'paused') {
                return new PaymentMethodWithdrawalStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}