<?php

namespace App\Lists\Withdrawal\Receipt\Status;

/**
 * Class WithdrawalReceiptStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $count
 *
 * @package App\Lists\Withdrawal\Receipt\Status
 */
class WithdrawalReceiptStatusListItem
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
     * WithdrawalReceiptStatusListItem constructor
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
    public function isPendingRequest() : bool
    {
        return $this->code == 'pending_request';
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
    public function isPaid() : bool
    {
        return $this->code == 'paid';
    }

    /**
     * @return bool
     */
    public function isCredit() : bool
    {
        return $this->code == 'credit';
    }

    /**
     * @return bool
     */
    public function isDeclinedRequest() : bool
    {
        return $this->code == 'declined_request';
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