<?php

namespace App\Lists\Order\Item\Sale\SortBy;

/**
 * Class OrderItemSaleSortByListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Order\Item\Sale\SortBy
 */
class OrderItemSaleSortByListItem
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
     * OrderItemSaleSortByListItem constructor
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
    public function isLatestSalesFirst() : bool
    {
        return $this->code == 'latest_sales_first';
    }

    /**
     * @return bool
     */
    public function isEarliestSalesFirst() : bool
    {
        return $this->code == 'earliest_sales_first';
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