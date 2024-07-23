<?php

namespace App\Lists\Request\Field\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RequestFieldStatusList
 *
 * @package App\Lists\Request\Field\Status
 */
class RequestFieldStatusList extends BaseList
{
    /**
     * List name
     */
    protected const LIST = 'request/field/status';

    /**
     * Default item id
     */
    protected const DEFAULT = 1;

    /**
     * Request field statuses list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'pending'
        ],
        [
            'id'   => 2,
            'code' => 'declined'
        ],
        [
            'id'   => 3,
            'code' => 'accepted'
        ],
        [
            'id'   => 4,
            'code' => 'canceled'
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
                new RequestFieldStatusListItem(
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
     * @return RequestFieldStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?RequestFieldStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new RequestFieldStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public static function getDefaultItem() : ?RequestFieldStatusListItem
    {
        $appendedItem = static::getAppendedItem(
            static::DEFAULT
        );

        if ($appendedItem) {
            return new RequestFieldStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public static function getPendingItem() : ?RequestFieldStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'pending') {
                return new RequestFieldStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public static function getDeclinedItem() : ?RequestFieldStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'declined') {
                return new RequestFieldStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public static function getAcceptedItem() : ?RequestFieldStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'accepted') {
                return new RequestFieldStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public static function getCanceledItem() : ?RequestFieldStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'canceled') {
                return new RequestFieldStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @param int|null $id
     *
     * @return bool
     */
    public static function isAcceptedItem(
        ?int $id
    ) : bool
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            if ($appendedItem['code'] == 'accepted') {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int|null $id
     *
     * @return bool
     */
    public static function isDeclinedItem(
        ?int $id
    ) : bool
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            if ($appendedItem['code'] == 'declined') {
                return true;
            }
        }

        return false;
    }
}