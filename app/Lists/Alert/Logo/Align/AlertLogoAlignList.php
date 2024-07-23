<?php

namespace App\Lists\Alert\Logo\Align;

use App\Lists\Alert\Type\AlertTypeListItem;
use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AlertLogoAlignList
 *
 * @package App\Lists\Alert\Logo\Align
 */
class AlertLogoAlignList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'alert/logo/align';

    /**
     * Alert logo aligns list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'none'
        ],
        [
            'id'   => 2,
            'code' => 'bottom_center'
        ],
        [
            'id'   => 3,
            'code' => 'top_center'
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
                new AlertLogoAlignListItem(
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
     * @return AlertLogoAlignListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?AlertLogoAlignListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new AlertLogoAlignListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @param AlertTypeListItem $alertTypeListItem
     *
     * @return AlertLogoAlignListItem|null
     */
    public static function getDefault(
        AlertTypeListItem $alertTypeListItem
    ) : ?AlertLogoAlignListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == config('alert.' . $alertTypeListItem->code . '.logo_align')) {
                return new AlertLogoAlignListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertLogoAlignListItem|null
     */
    public static function getNone() : ?AlertLogoAlignListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'none') {
                return new AlertLogoAlignListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertLogoAlignListItem|null
     */
    public static function getBottomCenter() : ?AlertLogoAlignListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'bottom_center') {
                return new AlertLogoAlignListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertLogoAlignListItem|null
     */
    public static function getTopCenter() : ?AlertLogoAlignListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'top_center') {
                return new AlertLogoAlignListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}