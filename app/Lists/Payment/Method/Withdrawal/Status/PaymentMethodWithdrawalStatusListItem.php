<?php

namespace App\Lists\Payment\Method\Withdrawal\Status;

/**
 * Class PaymentMethodWithdrawalStatusListItem
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @package App\Lists\Payment\Method\Withdrawal\Status
 */
class PaymentMethodWithdrawalStatusListItem
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
     * PaymentMethodWithdrawalStatusListItem constructor
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
    public function isActive() : bool
    {
        return $this->code == 'active';
    }

    /**
     * @return bool
     */
    public function isInactive() : bool
    {
        return $this->code == 'inactive';
    }

    /**
     * @return bool
     */
    public function isPaused() : bool
    {
        return $this->code == 'paused';
    }
}