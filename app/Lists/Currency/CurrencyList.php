<?php

namespace App\Lists\Currency;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CurrencyList
 *
 * @package App\Lists\Currency
 */
class CurrencyList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'currency';

    /**
     * Currencies list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'usd'
        ],
        [
            'id'   => 2,
            'code' => 'aud'
        ],
        [
            'id'   => 3,
            'code' => 'bgn'
        ],
        [
            'id'   => 4,
            'code' => 'brl'
        ],
        [
            'id'   => 5,
            'code' => 'cad'
        ],
        [
            'id'   => 6,
            'code' => 'chf'
        ],
        [
            'id'   => 7,
            'code' => 'cny'
        ],
        [
            'id'   => 8,
            'code' => 'czk'
        ],
        [
            'id'   => 9,
            'code' => 'dkk'
        ],
        [
            'id'   => 10,
            'code' => 'eur'
        ],
        [
            'id'   => 11,
            'code' => 'gbp'
        ],
        [
            'id'   => 12,
            'code' => 'hkd'
        ],
        [
            'id'   => 13,
            'code' => 'huf'
        ],
        [
            'id'   => 14,
            'code' => 'idr'
        ],
        [
            'id'   => 15,
            'code' => 'inr'
        ],
        [
            'id'   => 16,
            'code' => 'jpy'
        ],
        [
            'id'   => 17,
            'code' => 'myr'
        ],
        [
            'id'   => 18,
            'code' => 'nok'
        ],
        [
            'id'   => 19,
            'code' => 'nzd'
        ],
        [
            'id'   => 20,
            'code' => 'pln'
        ],
        [
            'id'   => 21,
            'code' => 'ron'
        ],
        [
            'id'   => 22,
            'code' => 'try'
        ],
        [
            'id'   => 23,
            'code' => 'sek'
        ],
        [
            'id'   => 24,
            'code' => 'sgd'
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
                new CurrencyListItem(
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
     * @return CurrencyListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?CurrencyListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new CurrencyListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getUsd() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'usd') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getAud() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'aud') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getBgn() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'bgn') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getBrl() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'brl') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getCad() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'cad') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getChf() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'chf') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getCny() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'cny') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getCzk() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'czk') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getDkk() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'dkk') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getEur() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'eur') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getGbr() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'gbr') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getHkd() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'hkd') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getHuf() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'huf') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getIdr() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'idr') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getInr() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'inr') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getJpy() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'jpy') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getMyr() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'myr') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getNok() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'nok') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getNzd() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'nzd') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getPln() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'pln') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getRon() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'ron') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getTry() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'try') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getSek() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'sek') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return CurrencyListItem|null
     */
    public static function getSgd() : ?CurrencyListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'sgd') {
                return new CurrencyListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}