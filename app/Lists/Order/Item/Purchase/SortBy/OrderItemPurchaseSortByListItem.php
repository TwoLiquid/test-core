<?php

namespace App\Lists\Order\Item\Purchase\SortBy;

/**
 * Class OrderItemPurchaseSortByListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Order\Item\Purchase\SortBy
 */
class OrderItemPurchaseSortByListItem
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $name;

    /**
     * OrderItemPurchaseSortByListItem constructor
     *
     * @param int $id
     * @param string $code
     * @param string $name
     */
    public function __construct(
        int $id,
        string $code,
        string $name
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isLatestPurchasesFirst() : bool
    {
        return $this->code == 'latest_purchases_first';
    }

    /**
     * @return bool
     */
    public function isEarliestPurchasesFirst() : bool
    {
        return $this->code == 'earliest_purchases_first';
    }

    /**
     * @return bool
     */
    public function isEarliestStartingVybesFirst() : bool
    {
        return $this->code == 'earliest_starting_vybes_first';
    }

    /**
     * @return bool
     */
    public function isLatestStartingVybesFirst() : bool
    {
        return $this->code == 'latest_starting_vybes_first';
    }
}