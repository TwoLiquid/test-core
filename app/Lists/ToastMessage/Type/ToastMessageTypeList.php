<?php

namespace App\Lists\ToastMessage\Type;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ToastMessageTypeList
 *
 * @package App\Lists\ToastMessage\Type
 */
class ToastMessageTypeList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'toastMessage/type';

    /**
     * Toast message types list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'submitted'
        ],
        [
            'id'   => 2,
            'code' => 'canceled'
        ],
        [
            'id'   => 3,
            'code' => 'accepted'
        ],
        [
            'id'   => 4,
            'code' => 'declined'
        ],
        [
            'id'   => 5,
            'code' => 'deletion'
        ],
        [
            'id'   => 6,
            'code' => 'warning'
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
                new ToastMessageTypeListItem(
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
     * @return ToastMessageTypeListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?ToastMessageTypeListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new ToastMessageTypeListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return ToastMessageTypeListItem|null
     */
    public static function getSubmittedItem() : ?ToastMessageTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'submitted') {
                return new ToastMessageTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return ToastMessageTypeListItem|null
     */
    public static function getCanceledItem() : ?ToastMessageTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'canceled') {
                return new ToastMessageTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return ToastMessageTypeListItem|null
     */
    public static function getAcceptedItem() : ?ToastMessageTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'accepted') {
                return new ToastMessageTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return ToastMessageTypeListItem|null
     */
    public static function getDeclinedItem() : ?ToastMessageTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'declined') {
                return new ToastMessageTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return ToastMessageTypeListItem|null
     */
    public static function getDeletionItem() : ?ToastMessageTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'deletion') {
                return new ToastMessageTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return ToastMessageTypeListItem|null
     */
    public static function getWarningItem() : ?ToastMessageTypeListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'warning') {
                return new ToastMessageTypeListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}