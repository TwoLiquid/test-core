<?php

namespace App\Lists\Vybe\Appearance;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VybeAppearanceList
 *
 * @package App\Lists\Vybe\Appearance
 */
class VybeAppearanceList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'vybe/appearance';

    /**
     * Vybe appearances list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'voice_chat'
        ],
        [
            'id'   => 2,
            'code' => 'video_chat'
        ],
        [
            'id'   => 3,
            'code' => 'real_life'
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
                new VybeAppearanceListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                )
            );
        }

        return $items;
    }

    /**
     * @param array|null $ids
     * 
     * @return Collection
     */
    public static function getItemsByIds(
        ?array $ids
    ) : Collection
    {
        $appendedItems = static::getAppendedItemsByIds($ids);

        $items = new Collection();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            $items->push(
                new VybeAppearanceListItem(
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
     * @return VybeAppearanceListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?VybeAppearanceListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new VybeAppearanceListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return VybeAppearanceListItem|null
     */
    public static function getVoiceChat() : ?VybeAppearanceListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'voice_chat') {
                return new VybeAppearanceListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeAppearanceListItem|null
     */
    public static function getVideoChat() : ?VybeAppearanceListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'video_chat') {
                return new VybeAppearanceListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VybeAppearanceListItem|null
     */
    public static function getRealLife() : ?VybeAppearanceListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'real_life') {
                return new VybeAppearanceListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}