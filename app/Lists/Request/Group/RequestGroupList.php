<?php

namespace App\Lists\Request\Group;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RequestGroupList
 *
 * @package App\Lists\Request\Group
 */
class RequestGroupList extends BaseList
{
    /**
     * List name
     */
    protected const LIST = 'request/group';

    /**
     * Request group list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'users'
        ],
        [
            'id'   => 2,
            'code' => 'vybes'
        ],
        [
            'id'   => 3,
            'code' => 'finance'
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
                new RequestGroupListItem(
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
     * @return RequestGroupListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?RequestGroupListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new RequestGroupListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return RequestGroupListItem|null
     */
    public static function getUser() : ?RequestGroupListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'users') {
                return new RequestGroupListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestGroupListItem|null
     */
    public static function getVybe() : ?RequestGroupListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'vybes') {
                return new RequestGroupListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return RequestGroupListItem|null
     */
    public static function getFinance() : ?RequestGroupListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'finance') {
                return new RequestGroupListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}