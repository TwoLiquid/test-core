<?php

namespace App\Lists\Payment\Method\Field\Type;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PaymentMethodFieldTypeList
 *
 * @package App\Lists\Payment\Method\Field\Type
 */
class PaymentMethodFieldTypeList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'payment/method/field/type';

    /**
     * a payment method field type list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'string'
        ],
        [
            'id'   => 2,
            'code' => 'integer'
        ],
        [
            'id'   => 3,
            'code' => 'boolean'
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
                new PaymentMethodFieldTypeListItem(
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
     * @return PaymentMethodFieldTypeListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?PaymentMethodFieldTypeListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new PaymentMethodFieldTypeListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return PaymentMethodFieldTypeListItem|null
     */
    public static function getString() : ?PaymentMethodFieldTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'string') {
                return new PaymentMethodFieldTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PaymentMethodFieldTypeListItem|null
     */
    public static function getInteger() : ?PaymentMethodFieldTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'integer') {
                return new PaymentMethodFieldTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PaymentMethodFieldTypeListItem|null
     */
    public static function getBoolean() : ?PaymentMethodFieldTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'boolean') {
                return new PaymentMethodFieldTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}
