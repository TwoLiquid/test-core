<?php

namespace App\Lists\Invoice\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class InvoiceStatusList
 *
 * @package App\Lists\Invoice\Status
 */
class InvoiceStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'invoice/status';

    /**
     * Invoice statuses list constant
     */
    protected const ITEMS = [
        [
            'id'          => 1,
            'code'        => 'unpaid',
            'attachments' => [
                'buyer',
                'tip_buyer',
//                'custom',
                'add_funds'
            ]
        ],
        [
            'id'          => 2,
            'code'        => 'on_hold',
            'attachments' => [
                'seller',
                'tip_seller'
            ]
        ],
        [
            'id'          => 3,
            'code'        => 'pending_payout',
            'attachments' => [
                'seller',
                'tip_seller'
            ]
        ],
        [
            'id'          => 4,
            'code'        => 'paid',
            'attachments' => [
                'buyer',
                'seller',
                'tip_buyer',
                'tip_seller',
//                'custom'
            ]
        ],
        [
            'id'          => 5,
            'code'        => 'canceled',
            'attachments' => [
                'buyer',
                'seller',
                'tip_buyer',
//                'custom'
            ]
        ],
        [
            'id'          => 6,
            'code'        => 'credit',
            'attachments' => [
                'buyer',
                'seller',
                'tip_buyer',
                'tip_seller',
//                'custom'
            ]
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
                new InvoiceStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                )
            );
        }

        return $items;
    }

    /**
     * @param array|null $ids
     *
     * @return Collection
     */
    public static function getItemsByIds(
        ?array $ids
    ) : Collection
    {
        $appendedItems = static::getAppendedItemsByIds($ids);

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            $items->push(
                new InvoiceStatusListItem(
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
     * @return InvoiceStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?InvoiceStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new InvoiceStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
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
            if (in_array('buyer', $appendedItem['attachments'])) {
                $items->push(
                    new InvoiceStatusListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name']
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
            if (in_array('seller', $appendedItem['attachments'])) {
                $items->push(
                    new InvoiceStatusListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @return Collection
     */
    public static function getAllForTipBuyer() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if (in_array('tip_buyer', $appendedItem['attachments'])) {
                $items->push(
                    new InvoiceStatusListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name']
                    )
                );
            }
        }

        return $items;
    }

    /**
     * @return Collection
     */
    public static function getAllForTipSeller() : Collection
    {
        $appendedItems = static::getAppendedItems();

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if (in_array('tip_seller', $appendedItem['attachments'])) {
                $items->push(
                    new InvoiceStatusListItem(
                        $appendedItem['id'],
                        $appendedItem['code'],
                        $appendedItem['name']
                    )
                );
            }
        }

        return $items;
    }

//    /**
//     * @return Collection
//     */
//    public static function getAllForCustom() : Collection
//    {
//        $appendedItems = static::getAppendedItems();
//
//        $items = new Collection();
//
//        /** @var array $appendedItem */
//        foreach ($appendedItems as $appendedItem) {
//            if (in_array('custom', $appendedItem['attachments'])) {
//                $items->push(
//                    new InvoiceStatusListItem(
//                        $appendedItem['id'],
//                        $appendedItem['code'],
//                        $appendedItem['name']
//                    )
//                );
//            }
//        }
//
//        return $items;
//    }

    /**
     * @return InvoiceStatusListItem|null
     */
    public static function getUnpaid() : ?InvoiceStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'unpaid') {
                return new InvoiceStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return InvoiceStatusListItem|null
     */
    public static function getOnHold() : ?InvoiceStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'on_hold') {
                return new InvoiceStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return InvoiceStatusListItem|null
     */
    public static function getPendingPayout() : ?InvoiceStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'pending_payout') {
                return new InvoiceStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return InvoiceStatusListItem|null
     */
    public static function getPaid() : ?InvoiceStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'paid') {
                return new InvoiceStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return InvoiceStatusListItem|null
     */
    public static function getCanceled() : ?InvoiceStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'canceled') {
                return new InvoiceStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return InvoiceStatusListItem|null
     */
    public static function getCredit() : ?InvoiceStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'credit') {
                return new InvoiceStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}