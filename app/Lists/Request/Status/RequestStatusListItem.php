<?php

namespace App\Lists\Request\Status;

/**
 * Class RequestStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $count
 *
 * @package App\Lists\Request\Status
 */
class RequestStatusListItem
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
     * RequestStatusListItem constructor
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
    public function isPending() : bool
    {
        return $this->code == 'pending';
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