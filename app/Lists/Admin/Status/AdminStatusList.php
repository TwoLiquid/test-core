<?php

namespace App\Lists\Admin\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AdminStatusList
 *
 * @package App\Lists\Admin\Status
 */
class AdminStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'admin/status';

    /**
     * Admin statuses list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'active'
        ],
        [
            'id'   => 2,
            'code' => 'disable'
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
                new AdminStatusListItem(
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
     * @return AdminStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?AdminStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new AdminStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return AdminStatusListItem|null
     */
    public static function getActive() : ?AdminStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'active') {
                return new AdminStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AdminStatusListItem|null
     */
    public static function getDisable() : ?AdminStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'disable') {
                return new AdminStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}