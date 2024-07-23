<?php

namespace App\Lists\VatNumberProof\Status;

use App\Lists\BaseList;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class VatNumberProofStatusList
 *
 * @package App\Lists\VatNumberProof\Status
 */
class VatNumberProofStatusList extends BaseList
{
    /**
     * List name
     *
     * @var string
     */
    protected const LIST = 'vatNumberProof/status';

    /**
     * Vat number proof statuses list constant
     */
    protected const ITEMS = [
        [
            'id'   => 1,
            'code' => 'inactive'
        ],
        [
            'id'   => 2,
            'code' => 'active'
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
                new VatNumberProofStatusListItem(
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
     * @return VatNumberProofStatusListItem|null
     */
    public static function getItem(
        ?int $id
    ) : ?VatNumberProofStatusListItem
    {
        $appendedItem = static::getAppendedItem($id);

        if ($appendedItem) {
            return new VatNumberProofStatusListItem(
                $appendedItem['id'],
                $appendedItem['code'],
                $appendedItem['name']
            );
        }

        return null;
    }

    /**
     * @return VatNumberProofStatusListItem|null
     */
    public static function getInactiveItem() : ?VatNumberProofStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'inactive') {
                return new VatNumberProofStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }

    /**
     * @return VatNumberProofStatusListItem|null
     */
    public static function getActiveItem() : ?VatNumberProofStatusListItem
    {
        $appendedItems = static::getAppendedItems();

        /** @var array $appendedItem */
        foreach ($appendedItems as $appendedItem) {
            if ($appendedItem['code'] == 'active') {
                return new VatNumberProofStatusListItem(
                    $appendedItem['id'],
                    $appendedItem['code'],
                    $appendedItem['name']
                );
            }
        }

        return null;
    }
}