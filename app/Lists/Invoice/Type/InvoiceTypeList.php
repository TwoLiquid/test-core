<?php

namespace App\Lists\Invoice\Type;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class InvoiceTypeList
 *
 * @package App\Lists\Invoice\Type
 */
class InvoiceTypeList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'invoice/type';

    /**
     * an invoice types list constant
     */
    protected const ITEMS = [
        [
            'id'         => 1,
            'code'       => 'buyer',
            'id_prefix'  => 'IB',
            'attachment' => 'buyer'
        ],
        [
            'id'         => 2,
            'code'       => 'seller',
            'id_prefix'  => 'IS',
            'attachment' => 'seller'
        ],
        [
            'id'         => 3,
            'code'       => 'affiliate',
            'id_prefix'  => 'IA',
            'attachment' => null
        ],
        [
            'id'         => 4,
            'code'       => 'credit_buyer',
            'id_prefix'  => 'CIB',
            'attachment' => 'buyer'
        ],
        [
            'id'         => 5,
            'code'       => 'credit_seller',
            'id_prefix'  => 'CIS',
            'attachment' => 'seller'
        ],
        [
            'id'         => 6,
            'code'       => 'tip_buyer',
            'id_prefix'  => 'TIB',
            'attachment' => 'buyer'
        ],
        [
            'id'         => 7,
            'code'       => 'tip_seller',
            'id_prefix'  => 'TIS',
            'attachment' => 'seller'
        ],
        [
            'id'         => 8,
            'code'       => 'custom',
            'id_prefix'  => 'CI',
            'attachment' => null
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
                new InvoiceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix'],
                    $appendedItem['attachment']
                )
            );
        }

        return $items;
    }

    /**
     * @param int|null $id
     *
     * @return InvoiceTypeListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?InvoiceTypeListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new InvoiceTypeListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name'],
                $appendedItem['id_prefix'],
                $appendedItem['attachment']
            );
        }

        return null;
    }

    /**
     * @return Collection
     */
    public static function getAllForBuyer() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['attachment'] == 'buyer') {
                $items->push(
                    new InvoiceTypeListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name'],
                        $appendedItem['id_prefix'],
                        $appendedItem['attachment']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @return Collection
     */
    public static function getAllForSeller() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['attachment'] == 'seller') {
                $items->push(
                    new InvoiceTypeListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name'],
                        $appendedItem['id_prefix'],
                        $appendedItem['attachment']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @return InvoiceTypeListItem|null
     */
    public static function getBuyer() : ?InvoiceTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'buyer') {
                return new InvoiceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix'],
                    $appendedItem['attachment']
                );
            }
        }

        return null;
    }

    /**
     * @return InvoiceTypeListItem|null
     */
    public static function getSeller() : ?InvoiceTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'seller') {
                return new InvoiceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix'],
                    $appendedItem['attachment']
                );
            }
        }

        return null;
    }

    /**
     * @return InvoiceTypeListItem|null
     */
    public static function getAffiliate() : ?InvoiceTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'affiliate') {
                return new InvoiceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix'],
                    $appendedItem['attachment']
                );
            }
        }

        return null;
    }

    /**
     * @return InvoiceTypeListItem|null
     */
    public static function getCreditBuyer() : ?InvoiceTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'credit_buyer') {
                return new InvoiceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix'],
                    $appendedItem['attachment']
                );
            }
        }

        return null;
    }

    /**
     * @return InvoiceTypeListItem|null
     */
    public static function getCreditSeller() : ?InvoiceTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'credit_seller') {
                return new InvoiceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix'],
                    $appendedItem['attachment']
                );
            }
        }

        return null;
    }

    /**
     * @return InvoiceTypeListItem|null
     */
    public static function getTipBuyer() : ?InvoiceTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'tip_buyer') {
                return new InvoiceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix'],
                    $appendedItem['attachment']
                );
            }
        }

        return null;
    }

    /**
     * @return InvoiceTypeListItem|null
     */
    public static function getTipSeller() : ?InvoiceTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'tip_seller') {
                return new InvoiceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix'],
                    $appendedItem['attachment']
                );
            }
        }

        return null;
    }

    /**
     * @return InvoiceTypeListItem|null
     */
    public static function getCustom() : ?InvoiceTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'custom') {
                return new InvoiceTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name'],
                    $appendedItem['id_prefix'],
                    $appendedItem['attachment']
                );
            }
        }

        return null;
    }
}
