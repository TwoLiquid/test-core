<?php

namespace App\Lists\User\Balance\Type;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserBalanceTypeList
 *
 * @package App\Lists\User\Balance\Type
 */
class UserBalanceTypeList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'user/balance/type';

    /**
     * User balance types list constant
     */
    protected const ITEMS = [
        [
            'id'        => 1,
            'code'      => 'buyer',
            'id_prefix' => 'BA'
        ],
        [
            'id'        => 2,
            'code'      => 'seller',
            'id_prefix' => 'SA'
        ],
        [
            'id'        => 3,
            'code'      => 'affiliate',
            'id_prefix' => 'AA'
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
                new UserBalanceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix']
                )
            );
        }

        return $items;
    }

    /**
     * @param int|null $id
     *
     * @return UserBalanceTypeListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?UserBalanceTypeListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new UserBalanceTypeListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name'],
                $appendedItem['id_prefix']
            );
        }

        return null;
    }

    /**
     * @return UserBalanceTypeListItem|null
     */
    public static function getBuyer() : ?UserBalanceTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'buyer') {
                return new UserBalanceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix']
                );
            }
        }

        return null;
    }

    /**
     * @return UserBalanceTypeListItem|null
     */
    public static function getSeller() : ?UserBalanceTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'seller') {
                return new UserBalanceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix']
                );
            }
        }

        return null;
    }

    /**
     * @return UserBalanceTypeListItem|null
     */
    public static function getAffiliate() : ?UserBalanceTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'affiliate') {
                return new UserBalanceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix']
                );
            }
        }

        return null;
    }
}