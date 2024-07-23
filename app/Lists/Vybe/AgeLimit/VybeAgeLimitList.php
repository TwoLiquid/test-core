<?php

namespace App\Lists\Vybe\AgeLimit;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeAgeLimitList
 * 
 * @package App\Lists\Vybe\AgeLimit
 */
class VybeAgeLimitList extends BaseList
{
    /**
     * List name
     * 
     * @var string
     */
    protected const LIST = 'vybe/ageLimit';

    /**
     * Vybe age limits list constant
     */
    protected const ITEMS = [
        [
            'id'    =>  1,
            'code'  =>  '0'
        ],
        [
            'id'    =>  2,
            'code'  =>  '16'
        ],
        [
            'id'    =>  3,
            'code'  =>  '18'
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
                new VybeAgeLimitListItem(
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
     * @return VybeAgeLimitListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?VybeAgeLimitListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new VybeAgeLimitListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return VybeAgeLimitListItem|null
     */
    public static function getZeroPlus() : ?VybeAgeLimitListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == '0') {
                return new VybeAgeLimitListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeAgeLimitListItem|null
     */
    public static function getSixteenPlus() : ?VybeAgeLimitListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == '16') {
                return new VybeAgeLimitListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeAgeLimitListItem|null
     */
    public static function getEighteenPlus() : ?VybeAgeLimitListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == '18') {
                return new VybeAgeLimitListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}