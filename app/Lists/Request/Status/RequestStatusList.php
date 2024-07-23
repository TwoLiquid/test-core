<?php

namespace App\Lists\Request\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RequestFieldStatusList
 *
 * @package App\Lists\Request\Status
 */
class RequestStatusList extends BaseList
{
    /**
     * List name
     */
    protected const LIST = 'request/status';

    /**
     * Default item id
     */
    protected const DEFAULT = 1;

    /**
     * Request statuses list constant
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
                new RequestStatusListItem(
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
     * @return RequestStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?RequestStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new RequestStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return RequestStatusListItem|null
     */
    public static function getDefaultItem() : ?RequestStatusListItem
    {
        $appendedItem = static::getAppendedItem(
            static::DEFAULT
        );

        if ($appendedItem) {
            return new RequestStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return RequestStatusListItem|null
     */
    public static function getPendingItem() : ?RequestStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'pending') {
                return new RequestStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestStatusListItem|null
     */
    public static function getDeclinedItem() : ?RequestStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'declined') {
                return new RequestStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestStatusListItem|null
     */
    public static function getAcceptedItem() : ?RequestStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'accepted') {
                return new RequestStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestStatusListItem|null
     */
    public static function getCanceledItem() : ?RequestStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'canceled') {
                return new RequestStatusListItem(
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
    public static function isCanceledItem(
        ?int $id
    ) : bool
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            if ($appendedItem['code'] == 'canceled') {
                return true;
            }
        }

        return false;
    }
}