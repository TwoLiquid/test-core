<?php

namespace App\Lists\Invoice\Status;

/**
 * Class InvoiceStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $count
 *
 * @package App\Lists\Invoice\Status
 */
class InvoiceStatusListItem
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
     * InvoiceStatusListItem constructor
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
    public function isOnHold() : bool
    {
        return $this->code == 'on_hold';
    }

    /**
     * @return bool
     */
    public function isPendingPayout() : bool
    {
        return $this->code == 'pending_payout';
    }

    /**
     * @return bool
     */
    public function isPaid() : bool
    {
        return $this->code == 'paid';
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
    public function isCredit() : bool
    {
        return $this->code == 'credit';
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