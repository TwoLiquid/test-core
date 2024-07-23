<?php

namespace App\Lists\Vybe\Access;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeAccessList
 *
 * @package App\Lists\Vybe\Access
 */
class VybeAccessList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'vybe/access';

    /**
     * Vybe accesses list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'public'
        ],
        [
            'id'   => 2,
            'code' => 'private'
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
                new VybeAccessListItem(
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
     * @return VybeAccessListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?VybeAccessListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new VybeAccessListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return VybeAccessListItem|null
     */
    public static function getPublic() : ?VybeAccessListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'public') {
                return new VybeAccessListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeAccessListItem|null
     */
    public static function getPrivate() : ?VybeAccessListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'private') {
                return new VybeAccessListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}