<?php

namespace App\Lists\Order\Item\Status;

/**
 * Class OrderItemStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $count
 *
 * @package App\Lists\Order\Item\Status
 */
class OrderItemStatusListItem
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
     * OrderItemStatusListItem constructor
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
    public function isUnpaid() : bool
    {
        return $this->code == 'unpaid';
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
    public function isInProcess() : bool
    {
        return $this->code == 'in_process';
    }

    /**
     * @return bool
     */
    public function isReschedule() : bool
    {
        return $this->code == 'reschedule';
    }

    /**
     * @return bool
     */
    public function isFinishRequest() : bool
    {
        return $this->code == 'finish_request';
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
    public function isCanceledDispute() : bool
    {
        return $this->code == 'canceled_dispute';
    }

    /**
     * @return bool
     */
    public function isDisputed() : bool
    {
        return $this->code == 'disputed';
    }

    /**
     * @return bool
     */
    public function isFinished() : bool
    {
        return $this->code == 'finished';
    }

    /**
     * @return bool
     */
    public function isFinishedDispute() : bool
    {
        return $this->code == 'finished_dispute';
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