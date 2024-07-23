<?php

namespace App\Lists\Order\Item\Request\Status;

/**
 * Class OrderItemRequestStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $count
 *
 * @package App\Lists\Order\Item\Request\Status
 */
class OrderItemRequestStatusListItem
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
     * @var int|null 
     */
    public ?int $count;

    /**
     * OrderItemRequestStatusListItem constructor
     * 
     * @param int $id
     * @param string $code
     * @param string $name
     * @param int|null $count
     */
    public function __construct(
        int $id,
        string $code,
        string $name,
        ?int $count = null
    )
    {
        /** @var int id */
        $this->id = $id;

        /** @var string code */
        $this->code = $code;

        /** @var string name */
        $this->name = $name;

        /** @var int count */
        $this->count = $count;
    }

    /**
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->code == 'active';
    }

    /**
     * @return bool
     */
    public function isFinished() : bool
    {
        return $this->code == 'finished';
    }

    /**
     * @return int|null
     */
    public function getCount() : ?int
    {
        return $this->count;
    }

    /**
     * @param int|null $count
     *
     * @return void
     */
    public function setCount(
        ?int $count
    ) : void
    {
        $this->count = $count ?: 0;
    }
}