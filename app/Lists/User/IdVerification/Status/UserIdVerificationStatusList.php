<?php

namespace App\Lists\User\IdVerification\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserIdVerificationStatusList
 *
 * @package App\Lists\User\IdVerification\Status
 */
class UserIdVerificationStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'user/idVerification/status';

    /**
     * User ID verification status list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'unverified'
        ],
        [
            'id'   => 2,
            'code' => 'pending'
        ],
        [
            'id'   => 3,
            'code' => 'verified'
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
                new UserIdVerificationStatusListItem(
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
     * @return UserIdVerificationStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?UserIdVerificationStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new UserIdVerificationStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return UserIdVerificationStatusListItem|null
     */
    public static function getUnverified() : ?UserIdVerificationStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'unverified') {
                return new UserIdVerificationStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return UserIdVerificationStatusListItem|null
     */
    public static function getPending() : ?UserIdVerificationStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'pending') {
                return new UserIdVerificationStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return UserIdVerificationStatusListItem|null
     */
    public static function getVerified() : ?UserIdVerificationStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'verified') {
                return new UserIdVerificationStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}