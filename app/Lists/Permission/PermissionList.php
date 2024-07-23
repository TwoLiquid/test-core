<?php

namespace App\Lists\Permission;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PermissionList
 *
 * @package App\Lists\Permission
 */
class PermissionList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'permission';

    /**
     * Permission list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'view'
        ],
        [
            'id'   => 2,
            'code' => 'edit'
        ],
        [
            'id'   => 3,
            'code' => 'add'
        ],
        [
            'id'   => 4,
            'code' => 'delete'
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
                new PermissionListItem(
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
     * @return PermissionListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?PermissionListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new PermissionListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @param string $code
     *
     * @return PermissionListItem|null
     */
    public static function getItemByCode(
        string $code
    ) : ?PermissionListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == $code) {
                return new PermissionListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PermissionListItem|null
     */
    public static function getView() : ?PermissionListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'view') {
                return new PermissionListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PermissionListItem|null
     */
    public static function getEdit() : ?PermissionListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'edit') {
                return new PermissionListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PermissionListItem|null
     */
    public static function getAdd() : ?PermissionListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'add') {
                return new PermissionListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return PermissionListItem|null
     */
    public static function getDelete() : ?PermissionListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'delete') {
                return new PermissionListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}