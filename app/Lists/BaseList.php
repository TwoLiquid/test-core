<?php

namespace App\Lists;

/**
 * Class BaseList
 *
 * @package App\Lists
 */
abstract class BaseList
{
    /**
     * Base list name
     */
    protected const LIST = '';

    /**
     * Base class list constant
     */
    protected const ITEMS = [];

    /**
     * List of fields requiring translation
     */
    protected const TRANSLATES = [];

    /**
     * @return array
     */
    public static function getAppendedItems() : array
    {
       $appendedList = [];

       /** @var array $listItem */
       foreach (static::ITEMS as $listItem) {

           /** @var string $translateItem */
           foreach (static::TRANSLATES as $translateItem) {
               $listItem[$translateItem] = static::getTranslate(
                   $listItem['code'],
                   $translateItem
               );
           }

           $appendedList[] = $listItem;
       }

       return $appendedList;
    }

    /**
     * @param array|null $ids
     * 
     * @return array
     */
    public static function getAppendedItemsByIds(
        ?array $ids
    ) : array
    {
        $appendedList = [];

        /** @var array $listItem */
        foreach (static::ITEMS as $listItem) {

            if (in_array($listItem['id'], $ids)) {

                /** @var string $translateItem */
                foreach (static::TRANSLATES as $translateItem) {
                    $listItem[$translateItem] = static::getTranslate(
                        $listItem['code'],
                        $translateItem
                    );
                }

                $appendedList[] = $listItem;
            }
        }

        return $appendedList;
    }

    /**
     * @param int|null $id
     *
     * @return array|null
     */
    public static function getAppendedItem(
        ?int $id
    ) : ?array
    {
        /** @var array $listItem */
        foreach (static::ITEMS as $listItem) {
            if ($listItem['id'] == $id) {

                /** @var string $translateItem */
                foreach (static::TRANSLATES as $translateItem) {
                    $listItem[$translateItem] = static::getTranslate(
                        $listItem['code'],
                        $translateItem
                    );
                }

                return $listItem;
            }
        }

        return null;
    }

    /**
     * @param string $itemCode
     * @param string $translateItem
     *
     * @return string|null
     */
    public static function getTranslate(
        string $itemCode,
        string $translateItem
    ) : ?string
    {
        $translate = null;

        $translationType = gettype(trans(
            'lists/' . static::LIST . '.' . $itemCode
        ));

        if ($translationType == 'string') {
            if ($translateItem == 'name') {
                $translate = trans(
                    'lists/' . static::LIST . '.' . $itemCode
                );
            }
        } elseif ($translationType == 'array') {
            $translate = trans(
                'lists/' . static::LIST . '.' . $itemCode
            )[$translateItem];
        }

        return $translate;
    }
}