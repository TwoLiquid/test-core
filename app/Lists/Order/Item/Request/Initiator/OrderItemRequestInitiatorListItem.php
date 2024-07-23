<?php

namespace App\Lists\Order\Item\Request\Initiator;

/**
 * Class OrderItemRequestInitiatorListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Order\Item\Request\Initiator
 */
class OrderItemRequestInitiatorListItem
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
     * OrderItemRequestInitiatorListItem constructor
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
    public function isBuyer() : bool
    {
        return $this->code == 'buyer';
    }

    /**
     * @return bool
     */
    public function isSeller() : bool
    {
        return $this->code == 'seller';
    }
}