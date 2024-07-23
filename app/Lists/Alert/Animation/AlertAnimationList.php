<?php

namespace App\Lists\Alert\Animation;

use App\Lists\Alert\Type\AlertTypeListItem;
use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AlertAnimationList
 *
 * @package App\Lists\Alert\Animation
 */
class AlertAnimationList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'alert/animation';

    /**
     * Alert animations list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'flip'
        ],
        [
            'id'   => 2,
            'code' => 'bounce'
        ],
        [
            'id'   => 3,
            'code' => 'flash'
        ],
        [
            'id'   => 4,
            'code' => 'pulse'
        ],
        [
            'id'   => 5,
            'code' => 'rubber_band'
        ],
        [
            'id'   => 6,
            'code' => 'shake_x'
        ],
        [
            'id'   => 7,
            'code' => 'shake_y'
        ],
        [
            'id'   => 8,
            'code' => 'head_shake'
        ],
        [
            'id'   => 9,
            'code' => 'swing'
        ],
        [
            'id'   => 10,
            'code' => 'ta_da'
        ],
        [
            'id'   => 11,
            'code' => 'wobble'
        ],
        [
            'id'   => 12,
            'code' => 'jello'
        ],
        [
            'id'   => 13,
            'code' => 'heart_beat'
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
                new AlertAnimationListItem(
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
     * @return AlertAnimationListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?AlertAnimationListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new AlertAnimationListItem(
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
     * @return AlertAnimationListItem|null
     */
    public static function getDefault(
        AlertTypeListItem $alertTypeListItem
    ) : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == config('alert.' . $alertTypeListItem->code . '.animation')) {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getFlip() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'flip') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getBounce() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'bounce') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getFlash() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'flash') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getPulse() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'pulse') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getRubberBand() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'rubber_band') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getShakeX() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'shake_x') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getShakeY() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'shake_y') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getHeadShake() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'head_shake') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getSwing() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'swing') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getTada() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'ta_da') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getWobble() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'wobble') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getJello() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'jello') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public static function getHeartBeat() : ?AlertAnimationListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'heart_beat') {
                return new AlertAnimationListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}