<?php

namespace App\Lists\Vybe\Showcase;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeShowcaseList
 *
 * @package App\Lists\Vybe\Showcase
 */
class VybeShowcaseList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'vybe/showcase';

    /**
     * Vybe showcases list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'profile_and_catalogs'
        ],
        [
            'id'   => 2,
            'code' => 'only_profile'
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
                new VybeShowcaseListItem(
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
     * @return VybeShowcaseListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?VybeShowcaseListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new VybeShowcaseListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return VybeShowcaseListItem|null
     */
    public static function getProfileAndCatalogs() : ?VybeShowcaseListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'profile_and_catalogs') {
                return new VybeShowcaseListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeShowcaseListItem|null
     */
    public static function getOnlyProfile() : ?VybeShowcaseListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'only_profile') {
                return new VybeShowcaseListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}