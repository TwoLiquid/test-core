<?php

namespace App\Lists\Vybe\OrderAccept;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeOrderAcceptList
 * 
 * @package App\Lists\Vybe\OrderAccept
 */
class VybeOrderAcceptList extends BaseList
{
    /**
     * List name
     * 
     * @var string
     */
    protected const LIST = 'vybe/orderAccept';

    /**
     * Vybe order accepts list constant
     */
    protected const ITEMS = [
        [
            'id'    =>  1,
            'code'  => 'manual'
        ],
        [
            'id'    =>  2,
            'code'  =>  'auto'
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
                new VybeOrderAcceptListItem(
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
     * @return VybeOrderAcceptListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?VybeOrderAcceptListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new VybeOrderAcceptListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return VybeOrderAcceptListItem|null
     */
    public static function getAuto() : ?VybeOrderAcceptListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'auto') {
                return new VybeOrderAcceptListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeOrderAcceptListItem|null
     */
    public static function getManual() : ?VybeOrderAcceptListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'manual') {
                return new VybeOrderAcceptListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}