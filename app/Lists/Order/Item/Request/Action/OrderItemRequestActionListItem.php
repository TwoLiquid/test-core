<?php

namespace App\Lists\Order\Item\Request\Action;

/**
 * Class OrderItemRequestActionListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Order\Item\Request\Action
 */
class OrderItemRequestActionListItem
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
     * OrderItemRequestActionListItem constructor
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
    public function isAccepted() : bool
    {
        return $this->code == 'accepted';
    }

    /**
     * @return bool
     */
    public function isCanceled() : bool
    {
        return $this->code == 'canceled';
    }

    /**
     * @return bool
     */
    public function isDeclined() : bool
    {
        return $this->code == 'declined';
    }

    /**
     * @return bool
     */
    public function isReschedule() : bool
    {
        return $this->code == 'reschedule';
    }
}